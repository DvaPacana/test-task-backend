<?php

namespace App\Http\Controllers\Api;

use App\Services\Payment\DTO\DTOBuilder;
use App\Services\Payment\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentGatewayController extends ApiController {

	public function handleCallback(Request $request, string $gateway, PaymentService $paymentService): JsonResponse
	{
		return response()->json(
			$paymentService->process(DTOBuilder::build((int) $gateway, $request->all(), $request->headers()), $gateway)
		);
	}
}
