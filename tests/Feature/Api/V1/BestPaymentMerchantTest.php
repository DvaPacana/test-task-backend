<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1;

use App\Enums\InvoiceStatus;
use App\Events\InvoiceWasUpdated;
use App\Events\UserBalanceWasUpdated;
use App\Models\Invoice;
use App\Models\Merchant;
use App\Models\User;
use App\Models\Wallet;
use App\Services\Merchants\Models\BestPaymentMerchant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Tests\TestCase;

final class BestPaymentMerchantTest extends TestCase
{
    private Invoice $invoice;
    private User $user;
    private array $payload;
    private Merchant $merchant;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();
        Queue::fake();

        $this->user = User::factory()
            ->has(Wallet::factory()->usd())->create();

        $this->invoice = Invoice::factory()->create([
            'status' => InvoiceStatus::CREATED,
            'user_id' => $this->user->id,
            'amount' => 500
        ]);

        $this->merchant = Merchant::factory()
            ->create(['external_id' => 816, 'daily_limit' => 100000]);

        $this->payload = [
            'project' => 816,
            'invoice' => $this->invoice->id,
            'status' => 'completed',
            'amount' => $this->invoice->amount,
            'amount_paid' =>  $this->invoice->amount,
            'rand' => 'SNuHufEJ',
        ];
    }

    /** @test */
    public function change_status_successful()
    {
        $currentAmount = $this->user->wallets()
            ->first()->balance;

        $response = $this->withExceptionHandling()->postJson(
            uri: route('api:v1:merchants:callback', [
                'merchant' => $this->payload['project']
            ]),
            data: $this->payload,
            headers: ['Authorization' => $this->makeSignature()]
        );

        $response->assertSuccessful();

        $this->assertDatabaseHas(Invoice::class, [
            'id' => $this->invoice->id,
            'status' => InvoiceStatus::COMPLETED
        ]);
        $this->assertDatabaseHas(Wallet::class, [
            'user_id' => $this->user->id,
            'currency' => Wallet::DEFAULT_CURRENCY,
            'balance' => $currentAmount + $this->payload['amount_paid']
        ]);

        Event::assertDispatched(InvoiceWasUpdated::class);
        Event::assertDispatched(UserBalanceWasUpdated::class);
    }

    /** @test */
    public function accruals_deferred()
    {
        $currentAmount = $this->user->wallets()
            ->first()->balance;

        $this->payload['amount_paid'] = 100000000;

        $response = $this->withExceptionHandling()->postJson(
            uri: route('api:v1:merchants:callback', [
                'merchant' => $this->payload['project']
            ]),
            data: $this->payload,
            headers: ['Authorization' => $this->makeSignature()]
        );

        $response->assertSuccessful();

        $this->assertDatabaseHas(Invoice::class, [
            'id' => $this->invoice->id,
            'status' => InvoiceStatus::DEFERRED,
            'deferred_expires_at' => Carbon::now()->addDay()
        ]);
        $this->assertDatabaseHas(Wallet::class, [
            'user_id' => $this->user->id,
            'currency' => Wallet::DEFAULT_CURRENCY,
            'balance' => $currentAmount
        ]);

        Event::assertDispatched(InvoiceWasUpdated::class);
        Event::assertNotDispatched(UserBalanceWasUpdated::class);
    }

    /** @test */
    public function invalid_signature()
    {
        $response = $this->withExceptionHandling()
            ->postJson(
                uri: route('api:v1:merchants:callback', [
                    'merchant' => $this->payload['project']
                ]),
                data: $this->payload,
                headers: ['Authorization' => 'test']
            );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonFragment(['merchant' => ['Invalid signature!']]);
    }

    /** @test */
    public function validate_fail_invoice()
    {
        $this->payload['invoice'] = Str::uuid();

        $response = $this->withExceptionHandling()
            ->postJson(
                uri: route('api:v1:merchants:callback', [
                    'merchant' => $this->payload['project']
                ]),
                data: $this->payload,
                headers: ['Authorization' => $this->makeSignature()]
            );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrorFor('invoice');
    }

    /** @test */
    public function validate_fail_status()
    {
        $this->payload['status'] = 'test';

        $response = $this->withExceptionHandling()
            ->postJson(
                uri: route('api:v1:merchants:callback', [
                    'merchant' => $this->payload['project']
                ]),
                data: $this->payload,
                headers: ['Authorization' => $this->makeSignature()]
            );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrorFor('status');
    }

    /** @test */
    public function validate_fail_amount()
    {
        $this->payload['amount'] = 'test';

        $response = $this->withExceptionHandling()
            ->postJson(
                uri: route('api:v1:merchants:callback', [
                    'merchant' => $this->payload['project']
                ]),
                data: $this->payload,
                headers: ['Authorization' => $this->makeSignature()]
            );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrorFor('amount');
    }

    /** @test */
    public function validate_fail_amount_paid()
    {
        $this->payload['amount_paid'] = 'test';

        $response = $this->withExceptionHandling()
            ->postJson(
                uri: route('api:v1:merchants:callback', [
                    'merchant' => $this->payload['project']
                ]),
                data: $this->payload,
                headers: ['Authorization' => $this->makeSignature()]
            );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrorFor('amount_paid');
    }

    /** @test */
    public function validate_fail_rand()
    {
        $this->payload['rand'] = null;

        $response = $this->withExceptionHandling()
            ->postJson(
                uri: route('api:v1:merchants:callback', [
                    'merchant' => $this->payload['project']
                ]),
                data: $this->payload,
                headers: ['Authorization' => $this->makeSignature()]
            );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrorFor('rand');
    }

    private function makeSignature(): string
    {
        $merchant = new BestPaymentMerchant(new Request());
        return $merchant->makeSignature($this->merchant->api_key, $this->payload);
    }
}
