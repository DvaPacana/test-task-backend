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
use App\Services\Merchants\Models\BeautifulPaymentMerchant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Tests\TestCase;

final class BeautifulPaymentMerchantTest extends TestCase
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

        $this->merchant = Merchant::factory()
            ->create(['external_id' => 6, 'daily_limit' => 100000]);

        $this->invoice = Invoice::factory()->create([
            'status' => InvoiceStatus::CREATED,
            'user_id' => $this->user->id,
            'merchant_id' => $this->merchant->id,
            'amount' => 500
        ]);

        $this->payload = [
            'merchant_id' => 6,
            'payment_id' => $this->invoice->id,
            'status' => 'completed',
            'amount' => $this->invoice->amount,
            'amount_paid' =>  $this->invoice->amount,
            'timestamp' => 1654103837,
        ];
    }

    /** @test */
    public function change_status_successful()
    {
        $currentAmount = $this->user->wallets()
            ->first()->balance;

        $response = $this->withExceptionHandling()->postJson(
            uri: route('api:v1:merchants:callback', [
                'merchant' => $this->payload['merchant_id']
            ]),
            data: $this->payloadWithSignature()
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
                'merchant' => $this->payload['merchant_id']
            ]),
            data: $this->payloadWithSignature()
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
        $this->payload['sign'] = 'test';

        $response = $this->withExceptionHandling()
            ->postJson(
                uri: route('api:v1:merchants:callback', [
                    'merchant' => $this->payload['merchant_id']
                ]),
                data: $this->payload,
            );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonFragment(['merchant' => ['Invalid signature!']]);
    }

    /** @test */
    public function validate_fail_payment_id()
    {
        $this->payload['payment_id'] = Str::uuid();

        $response = $this->withExceptionHandling()
            ->postJson(
                uri: route('api:v1:merchants:callback', [
                    'merchant' => $this->payload['merchant_id']
                ]),
                data: $this->payloadWithSignature()
            );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrorFor('payment_id');
    }

    /** @test */
    public function validate_fail_status()
    {
        $this->payload['status'] = 'test';

        $response = $this->withExceptionHandling()
            ->postJson(
                uri: route('api:v1:merchants:callback', [
                    'merchant' => $this->payload['merchant_id']
                ]),
                data: $this->payloadWithSignature()
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
                    'merchant' => $this->payload['merchant_id']
                ]),
                data: $this->payloadWithSignature()
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
                    'merchant' => $this->payload['merchant_id']
                ]),
                data: $this->payloadWithSignature()
            );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrorFor('amount_paid');
    }

    /** @test */
    public function validate_fail_timestamp()
    {
        $this->payload['timestamp'] = 'test';

        $response = $this->withExceptionHandling()
            ->postJson(
                uri: route('api:v1:merchants:callback', [
                    'merchant' => $this->payload['merchant_id']
                ]),
                data: $this->payloadWithSignature()
            );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrorFor('timestamp');
    }

    private function payloadWithSignature(): array
    {
        $merchant = new BeautifulPaymentMerchant(new Request());

        $this->payload['sign'] = $merchant->makeSignature(
            apiKey: $this->merchant->api_key,
            payload: $this->payload
        );

        return $this->payload;
    }
}
