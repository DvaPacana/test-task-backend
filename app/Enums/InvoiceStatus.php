<?php

declare(strict_types=1);

namespace App\Enums;

enum InvoiceStatus: string
{
    case CREATED = 'CREATED';
    case COMPLETED = 'COMPLETED';
    case DEFERRED = 'DEFERRED';

    public static function toArray(): array
    {
        return [
            static::CREATED->value,
            static::COMPLETED->value,
            static::DEFERRED->value,
        ];
    }
}
