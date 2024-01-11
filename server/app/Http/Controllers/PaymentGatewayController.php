<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentGatewayRequest;
use App\Payment\Contracts\PaymentGatewayInterface;

class PaymentGatewayController extends Controller
{
    public function handle(PaymentGatewayInterface $paymentGateway, PaymentGatewayRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $paymentGateway->extractData($request);
        $result = $paymentGateway->processPayment($data);

        return response()->json($result ? 200 : 404);
    }
}
