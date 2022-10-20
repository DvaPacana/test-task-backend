<?php

namespace App\Traits;

trait EnumsCommon
{
    public static function toArray(): array
    {
        return array_map(
            fn(self $enum) => $enum->value,
            self::cases()
        );
    }
}
