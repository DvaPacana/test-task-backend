<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\DTO\WalletData;
use App\Events\UserBalanceWasUpdated;
use App\Models\Wallet;

final class UpdateBalance
{
    public static function execute(WalletData $data): Wallet
    {
        $wallet = Wallet::query()
            ->firstOrCreate(
                attributes: [
                    'user_id' => $data->userId,
                    'currency' => $data->amount->getCurrency()->getCode()
                ],
            );

        $wallet->increment('balance', (int) $data->amount->getAmount());

        UserBalanceWasUpdated::dispatchIf($wallet->wasChanged('balance'), $wallet, $data);

        return $wallet;
    }
}
