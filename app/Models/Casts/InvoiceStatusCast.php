<?php

declare(strict_types=1);

namespace App\Models\Casts;

use App\Enums\InvoiceStatus;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

final class InvoiceStatusCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): InvoiceStatus
    {
        return InvoiceStatus::from($value);
    }

    public function set($model, string $key, $value, array $attributes): string
    {
        if ($value instanceof InvoiceStatus) {
            return $value->value;
        }

        return $value;
    }
}
