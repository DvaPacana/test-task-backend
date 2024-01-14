<?php

namespace App\Services\Payment\DTO;

use App\Models\Payment;

class SecondGatewayPaymentDTO extends AbstarctCallbackDTO {

    private const STATUS_MAP = [
        'created' => Payment::STATUS_NEW,
        'inprogress' => Payment::STATUS_PENDING,
        'paid' => Payment::STATUS_COMPLETED,
        'expired' => Payment::STATUS_EXPIRED,
        'rejected' => Payment::STATUS_REJECTED
    ];

	public function merchantId(): int
     {
		return $this->bodyData['project'];
	}

	public function paymentId():int
    {
		return $this->bodyData['invoice'];
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
		return $this->headersData['Authorization'];
	}
}
