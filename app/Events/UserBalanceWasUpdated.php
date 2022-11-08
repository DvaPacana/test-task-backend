<?php

namespace App\Events;

use App\DTO\WalletData;
use App\Models\Wallet;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserBalanceWasUpdated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly Wallet $wallet,
        public readonly WalletData $data
    ){
    }
}
