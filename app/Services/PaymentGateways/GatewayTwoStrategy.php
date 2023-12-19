<?php

namespace App\Services\PaymentGateways;

use App\Contracts\PaymentGatewayInterface;
use App\Trait\PaymentTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GatewayTwoStrategy implements PaymentGatewayInterface
{

    protected string $redisKey = 'gateway2:limit'; // Key для редиса

    protected int $limit = 150000; // Лимит в центах, 1500 долларов

    use PaymentTrait;

    public function validateCallback(Request $request): bool
    {
        $data = $request->only(['project', 'invoice', 'status', 'amount', 'amount_paid', 'rand']);
        ksort($data);
        $signatureString = implode('.', $data) . '.rTaasVHeteGbhwBx';
        $computedSignature = md5($signatureString);

        return $computedSignature === $request->header('Authorization');
    }

    public function processPayment(Request $request): JsonResponse
    {
        if ($this->isLimitExceeded($this->redisKey, $this->limit)) {
            return response()->json(['error' => 'Payment limit exceeded'], 429);
        }

        $this->updateLimit($this->redisKey, $this->limit);

        return $this->updatePayment($request->invoice, $request->status, $request->amount_paid);
    }
}
