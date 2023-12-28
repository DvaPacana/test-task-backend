<?php

namespace App\Http\Services\PaymentGateway;

use App\Models\Payment;

abstract class PaymentGateway
{
    abstract public function process(array $paymentData): Payment;
}
