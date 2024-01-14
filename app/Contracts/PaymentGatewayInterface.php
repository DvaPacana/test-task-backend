<?php

namespace App\Contracts;

interface PaymentGatewayInterface {

	public const LIMIT_MESSAGE = "Дневной лимит для данного шлюза превышен.";
}
