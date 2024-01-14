<?php

namespace App\Services\Payment;

use App\Repositories\PaymentRepository;
use App\Services\Payment\DTO\AbstarctCallbackDTO;
use App\Services\Sign\SignBuilder;
use Exception;
use App\Contracts\SignInterface;
use InvalidArgumentException;

class PaymentService {
	public function __construct(
		private readonly PaymentRepository $paymentRepository
	) {
	}

	/**
	 * @throws Exception
	 */
	public function process(AbstarctCallbackDTO $paymentData, string $gateway): array
	{
		$signer = SignBuilder::build((int) $gateway);

		$this->checkSign($signer, $paymentData);

		return $this->processPaymentData($paymentData, $gateway);
	}

	private function processPaymentData(AbstarctCallbackDTO $paymentData, string $gateway): array
	 {
		$payment = $this->paymentRepository->findByMerchantPaymentId($paymentData->paymentId(), (int) $gateway);

		if ($this->paymentRepository->save($payment, $paymentData)) {
			return ['success' => true];
		}

		return ['success' => false];
	}

	private function checkSign(SignInterface $signer, AbstarctCallbackDTO $paymentData): void
	{
		if ($signer->sign($paymentData->body()) !== $paymentData->sign()) {
			throw new InvalidArgumentException("Sign is incorrect");
		}
	}

}
