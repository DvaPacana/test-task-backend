<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Invoice;
use App\Models\Merchant;
use App\Models\User;
use Tests\TestCase;

final class InvoiceTest extends TestCase
{
    /** @test */
    public function invoice_has_relation_with_user()
    {
        $invoice = Invoice::factory()
            ->for(User::factory())
            ->create();

        $this->assertInstanceOf(User::class, $invoice->user()->first());
    }

    /** @test */
    public function invoice_has_relation_with_merchant()
    {
        $invoice = Invoice::factory()
            ->for(Merchant::factory())
            ->create();

        $this->assertInstanceOf(Merchant::class, $invoice->merchant()->first());
    }
}
