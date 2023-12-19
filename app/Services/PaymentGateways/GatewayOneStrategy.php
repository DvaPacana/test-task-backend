<?php

namespace App\Services\PaymentGateways;

use App\Contracts\PaymentGatewayInterface;
use App\Trait\PaymentTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GatewayOneStrategy implements PaymentGatewayInterface
{
    protected string $redisKey = 'gateway1:limit'; // Key для редиса

    protected int $limit = 100000; // Лимит в центах, 1000 долларов

    use PaymentTrait;

    public function validateCallback(Request $request): bool
    {
        $data = $request->except('sign');
        ksort($data);
        $signatureString = implode(':', $data) . ':KaTf5tZYHx4v7pgZ';
        $computedSignature = hash('sha256', $signatureString);

        return $computedSignature === $request->sign;
    }

    public function processPayment(Request $request): JsonResponse
    {
        if ($this->isLimitExceeded($this->redisKey, $this->limit)) {
            return response()->json(['error' => 'Payment limit exceeded'], 429);
        }

        $this->updateLimit($this->redisKey, $this->limit);

       return $this->updatePayment($request->payment_id, $request->status, $request->amount_paid);
    }
}
