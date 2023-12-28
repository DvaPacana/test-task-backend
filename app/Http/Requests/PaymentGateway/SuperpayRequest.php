<?php

namespace App\Http\Requests\PaymentGateway;

use App\Http\Enums\PaymentGateway\SuperpayPaymentStatus;
use Illuminate\Validation\Rule;

class SuperpayRequest extends PaymentGatewayRequest
{
    public function authorize(): bool
    {
        $attributes = $this->only([
            "project",
            "invoice",
            "status",
            "amount",
            "amount_paid",
            "rand",
        ]);

        ksort($attributes);

        $attributesJoined = implode('.', $attributes);

        $attributesJoined .= config('payment.drivers.superpay.key');

        $valuesHash = hash(config('payment.drivers.superpay.hash_driver'), $attributesJoined);

        return $this->header('Authorization') === $valuesHash;
    }

    public function rules(): array
    {
        return [
            "project" => ['required', 'integer'],
            "invoice" => ['required', 'integer'],
            "status" => ['required', 'string', Rule::enum(SuperpayPaymentStatus::class)],
            "amount" => ['required', 'integer', 'min:0'],
            "amount_paid" => ['required', 'integer', 'min:0'],
            "rand" => ['required', 'string'],
        ];
    }
}
