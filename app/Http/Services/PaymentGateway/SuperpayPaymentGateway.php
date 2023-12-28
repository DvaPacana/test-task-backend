<?php

namespace App\Http\Services\PaymentGateway;

use App\Models\Payment;

class SuperpayPaymentGateway extends PaymentGateway
{
    public function process(array $paymentData): Payment
    {
        return Payment::query()->updateOrCreate(
            ['payment_id' => $paymentData['project']],
            [
                'merchant_id' => $paymentData['project'],
                'payment_id' => $paymentData['invoice'],
                'status' => $paymentData['status'],
                'amount' => $paymentData['amount'],
                'amount_paid' => $paymentData['amount_paid'],
                'signature' => $paymentData['rand'],
            ]);
    }
}
