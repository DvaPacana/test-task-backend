<?php

namespace App\Http\Services\PaymentGateway;

use App\Models\Payment;

class GoldenpayPaymentGateway extends PaymentGateway
{
    public function process(array $paymentData): Payment
    {
        return Payment::query()->updateOrCreate(
            ['payment_id' => $paymentData['payment_id']],
            [
                'merchant_id' => $paymentData['merchant_id'],
                'payment_id' => $paymentData['payment_id'],
                'status' => $paymentData['status'],
                'amount' => $paymentData['amount'],
                'amount_paid' => $paymentData['amount_paid'],
                'signature' => $paymentData['sign'],
            ]);
    }
}
