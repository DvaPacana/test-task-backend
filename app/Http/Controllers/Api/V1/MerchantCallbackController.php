<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Actions\Invoices\UpdateStatus;
use App\Contracts\Merchant as MerchantInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\MerchantCallbackRequest;
use App\Http\Resources\InvoiceStatusResource;
use App\Models\Merchant;
use App\Services\Merchants\MerchantManager;
use Illuminate\Validation\ValidationException;

final class MerchantCallbackController extends Controller
{
    public function __construct(
        private MerchantManager $manager
    ) {
    }

    public function __invoke(MerchantCallbackRequest $request, Merchant $merchant): InvoiceStatusResource
    {
        /** @var MerchantInterface $helper */
        $helper = $this->manager->get($merchant->external_id);

        if ($helper->checkSignature($merchant->api_key, $request->validated())) {
            $invoice = UpdateStatus::execute($request->toDataObject());
            return InvoiceStatusResource::make($invoice);
        }

        throw ValidationException::withMessages([
            'merchant' => 'Invalid signature!'
        ]);
    }
}
