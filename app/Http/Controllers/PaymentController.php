<?php

namespace App\Http\Controllers;

use App\Contracts\PaymentGatewayInterface;
use App\Services\PaymentGateways\GatewayOneStrategy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * @throws \Exception
     */
    public function handleCallback(Request $request): JsonResponse
    {
        $gateway = $this->determineGateway($request);

        if (!$gateway->validateCallback($request)) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        return $gateway->processPayment($request);
    }

    private function determineGateway($request): PaymentGatewayInterface|\Exception {

        if ($request->has('merchant_id')) {
            return new GatewayOneStrategy();
        } else if ($request->has('project')) {
            return new GatewayOneStrategy();
        }

        throw new \Exception("Unknown gateway");
    }
}
