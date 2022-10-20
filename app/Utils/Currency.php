<?php

namespace App\Utils;

use App\Traits\ConvertVariable;
use Illuminate\Support\Facades\Facade;
use JetBrains\PhpStorm\Pure;

class Currency extends Facade
{
    use ConvertVariable;


    #[Pure] public static function convertCent(int $amount): float
    {
        return $amount / 100;
    }

    #[Pure] public static function convertRaw(float $amount): int
    {
        return $amount * 100;
    }
}
