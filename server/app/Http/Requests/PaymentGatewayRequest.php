<?php

namespace App\Http\Requests;

use App\Repositories\Interfaces\PaymentGatewayRepositoryInterface;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PaymentGatewayRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(PaymentGatewayRepositoryInterface $paymentGatewayRepository): array
    {
        $name = $this->route("paymentGateway");

        return $paymentGatewayRepository->findOrFail($name)->rules();
    }
}
