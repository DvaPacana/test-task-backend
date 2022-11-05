<?php

declare(strict_types=1);

namespace App\DTO;

use Illuminate\Contracts\Support\Arrayable;
use Money\Money;

final class WalletData implements Arrayable
{
    public function __construct(
        public readonly int $userId,
        public readonly Money $amount
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->userId,
            'amount' => $this->amount->getAmount(),
            'currency' => $this->amount->getCurrency()->getCode(),
        ];
    }
}
