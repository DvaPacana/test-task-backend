<?php

namespace App\Services\Payment\DTO;

abstract class AbstarctCallbackDTO {

	/**
	 * @param array $bodyData
     * @param array $headersData
	 */
	public function __construct(
        protected readonly array $bodyData,
        protected readonly array $headersData
        )
    {}

	abstract public function merchantId(): int;

	abstract public function paymentId(): int;

	abstract public function status(): int;

	abstract public function amount(): int;

	abstract public function amountPaid(): int;

	abstract public function sign(): string;

    public function body(): array
    {
        return $this->bodyData;
    }
}
