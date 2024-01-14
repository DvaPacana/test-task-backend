<?php

namespace App\Services\Payment\DTO;

use App\Models\Payment;

class FirstGatewayPaymentDTO extends AbstarctCallbackDTO {

    private const STATUS_MAP = [
        'new' => Payment::STATUS_NEW,
        'pending' => Payment::STATUS_PENDING,
        'completed' => Payment::STATUS_COMPLETED,
        'expired' => Payment::STATUS_EXPIRED,
        'rejected' => Payment::STATUS_REJECTED
    ];

	public function merchantId(): int
     {
		return $this->bodyData['merchant_id'];
	}

	public function paymentId():int
    {
		return $this->bodyData['payment_id'];
	}

	public function status(): int
    {
		return self::STATUS_MAP[$this->bodyData['status']];
	}

	public function amount(): int
    {
		return $this->bodyData['amount'];
	}

	public function amountPaid():int
    {
		return $this->bodyData['amount_paid'];
	}

	public function sign(): string
    {
		return $this->bodyData['sign'];
	}
}
