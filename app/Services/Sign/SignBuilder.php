<?php

namespace App\Services\Sign;

use App\Contracts\SignInterface;
use App\Models\Payment;
use InvalidArgumentException;

class SignBuilder {
	public static function build(int $gateway): SignInterface
	{
        $merchantKey = config("services.merchants.{$gateway}.key");

        return match ($gateway) {
			Payment::PAYMENT_GATEWAY_FIRST  => new SignSHA256($merchantKey),
			Payment::PAYMENT_GATEWAY_SECOND => new SignMD5($merchantKey),
			default => throw new InvalidArgumentException("Неизвестный тип платежного шлюза: {$gateway}"),
		};
	}
}
