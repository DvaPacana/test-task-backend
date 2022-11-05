<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Invoice;
use App\Models\User;
use App\Models\Wallet;
use Tests\TestCase;

final class UserTest extends TestCase
{
    /** @test */
    public function user_has_relation_with_wallet()
    {
        $user = User::factory()
            ->has(Wallet::factory())
            ->create();

        $this->assertInstanceOf(Wallet::class, $user->wallets()->first());
    }

    /** @test */
    public function user_has_relation_with_invoice()
    {
        $user = User::factory()
            ->has(Invoice::factory())
            ->create();

        $this->assertInstanceOf(Invoice::class, $user->invoices()->first());
    }
}
