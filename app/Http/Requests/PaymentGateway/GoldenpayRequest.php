<?php

namespace App\Http\Requests\PaymentGateway;

use App\Http\Enums\PaymentGateway\GoldenpayPaymentStatus;
use Illuminate\Validation\Rule;

class GoldenpayRequest extends PaymentGatewayRequest
{
    public function authorize(): bool
    {
        $attributes = $this->only([
            "merchant_id",
            "payment_id",
            "status",
            "amount",
            "amount_paid",
            "timestamp",
        ]);

        ksort($attributes);

        $attributesJoined = implode(':', $attributes);

        $attributesJoined .= config('payment.drivers.goldenpay.key');

        $valuesHash = hash(config('payment.drivers.goldenpay.hash_driver'), $attributesJoined);

        return $this->sign === $valuesHash;
    }

    public function rules(): array
    {
        return [
            "merchant_id" => ['required', 'integer'],
            "payment_id" => ['required', 'integer'],
            "status" => ['required', 'string', Rule::enum(GoldenpayPaymentStatus::class)],
            "amount" => ['required', 'integer', 'min:0'],
            "amount_paid" => ['required', 'integer', 'min:0'],
            "timestamp" => ['required', 'integer'],
            "sign" => ['required', 'string'],
        ];
    }
}
