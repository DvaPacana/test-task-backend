<?php

namespace App\Repositories\PaymentGateway;

use App\Models\Payment;
use App\Models\PaymentGateway;

interface PaymentGatewayRepositoryContract
{
    function findOrFail(string $name): PaymentGateway;
}