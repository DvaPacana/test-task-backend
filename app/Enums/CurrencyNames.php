<?php

namespace App\Enums;

use App\Traits\EnumsCommon;

enum CurrencyNames: string
{
    use EnumsCommon;

    case USD = 'USD';
    case EUR = 'EUR';

}
