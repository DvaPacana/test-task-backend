<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentGateway\PaymentGatewayRequest;
use App\Http\Services\PaymentGateway\PaymentGateway;
use Illuminate\Http\JsonResponse;
use function response;

class PaymentController extends Controller
{
    public function process(PaymentGatewayRequest $request, PaymentGateway $gateway): JsonResponse
    {
        $gateway->process($request->validated());

        $gatewayName = $request->route('gateway');
        $limiterName = config('payment.rate_limiter_name');

        \RateLimiter::hit($limiterName . $gatewayName);

        return response()->json(['success' => true]);
    }
}
