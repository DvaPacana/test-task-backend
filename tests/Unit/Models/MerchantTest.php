<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\Merchant;
use Carbon\Carbon;
use Tests\TestCase;

final class MerchantTest extends TestCase
{
    /** @test */
    public function merchant_has_relation_with_invoice()
    {
        $merchant = Merchant::factory()
            ->has(Invoice::factory())
            ->create();

        $this->assertInstanceOf(Invoice::class, $merchant->invoices()->first());
    }

    /** @test */
    public function available_limit()
    {
        $merchant = Merchant::factory()
            ->create(['daily_limit' => 1010]);

        Invoice::factory(10)
            ->create([
                'status' => InvoiceStatus::COMPLETED,
                'amount' => 100,
                'created_at' => Carbon::now()->subHours(10),
                'merchant_id' => $merchant->id
            ]);

        Invoice::factory(10)
            ->create([
                'status' => InvoiceStatus::COMPLETED,
                'created_at' => Carbon::now()->subDays(rand(2, 10)),
                'merchant_id' => $merchant->id
            ]);

        $this->assertEquals(10, $merchant->availableLimit());
    }

    /** @test */
    public function has_available_limit()
    {
        $merchant = Merchant::factory()
            ->create(['daily_limit' => 1010]);

        Invoice::factory(10)
            ->create([
                'amount' => 100,
                'status' => InvoiceStatus::COMPLETED,
                'created_at' => Carbon::now()->subHours(10),
                'merchant_id' => $merchant->id
            ]);

        Invoice::factory(10)
            ->create([
                'status' => InvoiceStatus::COMPLETED,
                'created_at' => Carbon::now()->subDays(rand(2, 10)),
                'merchant_id' => $merchant->id
            ]);

        $this->assertTrue($merchant->hasAvailableLimit());
    }

    /** @test */
    public function exceeded_limit()
    {
        $merchant = Merchant::factory()
            ->create(['daily_limit' => 500]);

        Invoice::factory(10)
            ->create([
                'status' => InvoiceStatus::COMPLETED,
                'amount' => 100,
                'created_at' => Carbon::now()->subHours(10),
                'merchant_id' => $merchant->id
            ]);


        $this->assertFalse($merchant->hasAvailableLimit());
    }
}
