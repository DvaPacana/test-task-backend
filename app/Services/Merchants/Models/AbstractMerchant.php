<?php

declare(strict_types=1);

namespace App\Services\Merchants\Models;

use Illuminate\Http\Request;

abstract class AbstractMerchant
{
    public function __construct(
        protected Request $request
    ) {
    }
}
