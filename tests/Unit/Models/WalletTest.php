<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Wallet;
use Tests\TestCase;

final class WalletTest extends TestCase
{
    /** @test */
    public function wallet_has_relation_with_user()
    {
        $wallet = Wallet::factory()
            ->for(User::factory())
            ->create();

        $this->assertInstanceOf(User::class, $wallet->user()->first());
    }
}
