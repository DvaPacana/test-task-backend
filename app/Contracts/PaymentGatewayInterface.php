<?php

namespace App\Contracts;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface PaymentGatewayInterface {
    public function validateCallback(Request $request): bool;

    public function processPayment(Request $request): JsonResponse;
}
