<?php

namespace App\Http\Requests\PaymentGateway;

use Illuminate\Foundation\Http\FormRequest;

abstract class PaymentGatewayRequest extends FormRequest
{
    abstract public function rules(): array;

    abstract public function authorize(): bool;
}
