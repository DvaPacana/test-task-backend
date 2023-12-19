<?php

namespace App\Trait;

use App\Helper\RedisCache;
use App\Repositories\PaymentRepository;
use Illuminate\Http\JsonResponse;

trait PaymentTrait
{
    public function __construct()
    {
        $this->redis = new RedisCache(0);
    }

    public function updatePayment(int $paymentId, string $status, int $amountPaid): JsonResponse
    {
        $payment = PaymentRepository::getByPaymentId($paymentId);

        if (!$payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }

        $payment->update([
            'status' => $status,
            'amount_paid' => $amountPaid
        ]);

        return response()->json(['success' => true]);
    }

    protected function isLimitExceeded(string $key, int $limit): bool {
        $currentAmount = $this->redis->getByKey($key);

        return $currentAmount >= $limit;
    }

    protected function updateLimit(string $key, int $amount): void {
        $currentAmount = $this->redis->getByKey($key) ?? 0;
        $this->redis->setMessageByKeyWithTtl($key, $currentAmount + $amount, 86400); // Срок действия - один день
    }
}
