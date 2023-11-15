<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentGatewayCallbackRequest;
use App\Payment\Contracts\PaymentGatewayContract;

class PaymentGatewayController extends Controller
{
    public function handleCallback(PaymentGatewayContract $paymentGateway, PaymentGatewayCallbackRequest $request)
    {
        $data = $paymentGateway->extractData($request);
        $result = $paymentGateway->processPayment($data);

        $code = $result ? 200 : 400;
        return response()->json(status:$code);
    }
}
