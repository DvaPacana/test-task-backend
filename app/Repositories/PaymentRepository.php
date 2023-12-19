<?php
namespace App\Repositories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;

class PaymentRepository
{
    public static function getByPaymentId(int $paymentId): Model
    {
        return Payment::where('payment_id', $paymentId)->first();
    }
}
