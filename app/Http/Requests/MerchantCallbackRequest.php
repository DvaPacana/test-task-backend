<?php

namespace App\Http\Requests;

use App\Contracts\Merchant;
use App\DTO\InvoiceData;
use App\Helpers\MerchantConfig;
use App\Models\Merchant as MerchantModel;
use App\Services\Merchants\MerchantManager;
use Illuminate\Foundation\Http\FormRequest;

class MerchantCallbackRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return $this->merchant()->rules();
    }

    public function toDataObject(): InvoiceData
    {
        return $this->merchant()
            ->makeInvoiceData(
                payload: $this->validated()
            );
    }

    private function merchant(): Merchant
    {
        return app(MerchantManager::class)
            ->get($this->merchantModel()->external_id);
    }

    private function merchantModel(): MerchantModel
    {
        return $this->route('merchant');
    }
}
