<?php

declare(strict_types=1);

namespace App\Services\Merchants;

use App\Contracts\Merchant;
use App\Services\Merchants\Exceptions\MerchantNotFoundException;
use App\Services\Merchants\Models\BeautifulPaymentMerchant;
use App\Services\Merchants\Models\BestPaymentMerchant;
use Illuminate\Http\Request;

final class MerchantManager
{
    private array $merchants = [
        6 => BeautifulPaymentMerchant::class,
        816 => BestPaymentMerchant::class,
    ];

    public function __construct(
        private Request $request
    ) {
    }

    public function get(int $externalId): Merchant
    {
        return isset($this->merchants[$externalId])
            ? new $this->merchants[$externalId]($this->request)
            : throw new MerchantNotFoundException(
                message: sprintf('Merchant [%s] not found!', $externalId)
            );
    }
}
