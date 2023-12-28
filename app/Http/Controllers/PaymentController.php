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

        return response()->json(['success' => true]);
    }
}
