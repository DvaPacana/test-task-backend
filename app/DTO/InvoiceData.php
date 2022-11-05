<?php

declare(strict_types=1);

namespace App\DTO;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Money\Currency;
use Money\Money;

final class InvoiceData implements Arrayable
{
    public static function makeFromModel(Invoice $model): static
    {
        return new static(
            id: $model->id,
            status: $model->status,
            amount: new Money(
                amount: $model->amount,
                currency: new Currency(Wallet::DEFAULT_CURRENCY->value)
            ),
            createdAt: $model->created_at
        );
    }

    public function __construct(
        public readonly int $id,
        public readonly InvoiceStatus $status,
        public readonly Money $amount,
        public readonly null|Carbon $createdAt = null
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status->value,
            'amount' => $this->amount->getAmount(),
            'currency' => $this->amount->getCurrency()->getCode(),
        ];
    }
}
