<?php

namespace App\Http\Requests;

use App\Repositories\PaymentGateway\PaymentGatewayRepositoryContract;
use Illuminate\Foundation\Http\FormRequest;

class PaymentGatewayCallbackRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(PaymentGatewayRepositoryContract $paymentGatewayRepository): array
    {
        $name = $this->route("paymentGateway");
        return $paymentGatewayRepository->findOrFail($name)->rules();
    }
}
