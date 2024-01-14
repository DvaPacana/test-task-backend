<?php

namespace App\Services\Payment\DTO;

use App\Models\Payment;
use InvalidArgumentException;

class DTOBuilder {
    public static function build(int $gateway, array $bodyData, array $headersData)
    {
        return match ($gateway) {
			Payment::PAYMENT_GATEWAY_FIRST  => new FirstGatewayPaymentDTO($bodyData, $headersData),
			Payment::PAYMENT_GATEWAY_SECOND => new SecondGatewayPaymentDTO($bodyData, $headersData),
			default  => throw new InvalidArgumentException("Неизвестный тип платежного шлюза: {$gateway}"),
		};
	}
}
