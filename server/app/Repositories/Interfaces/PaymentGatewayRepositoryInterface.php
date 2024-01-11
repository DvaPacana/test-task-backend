<?php

namespace App\Repositories\Interfaces;

use App\Models\PaymentGateway;

interface PaymentGatewayRepositoryInterface
{
    function findOrFail(string $name): PaymentGateway;
}
