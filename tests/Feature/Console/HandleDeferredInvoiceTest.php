<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Tests\TestCase;

final class HandleDeferredInvoiceTest extends TestCase
{
    private Invoice $invoice;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()
            ->create();

        Wallet::factory()
            ->create([
                'balance' => 500,
                'user_id' => $this->user->id,
                'currency' => Wallet::DEFAULT_CURRENCY
            ]);

        $this->invoice = Invoice::factory()
            ->create([
                'amount' => 100,
                'status' => InvoiceStatus::DEFERRED,
                'deferred_expires_at' => Carbon::now()->subDays(2),
                'user_id' => $this->user
            ]);
    }

    /** @test */
    public function success_handle_invoice()
    {
        $wallet = $this->user->wallets()->first();

        $this->artisan('handle:invoice:deferred')->assertSuccessful();

        $this->assertDatabaseHas(Invoice::class, [
            'id' => $this->invoice->id,
            'status' => InvoiceStatus::COMPLETED
        ]);

        $this->assertDatabaseHas(Wallet::class, [
            'id' => $wallet->id,
            'balance' => $wallet->balance + $this->invoice->amount
        ]);
    }
}
