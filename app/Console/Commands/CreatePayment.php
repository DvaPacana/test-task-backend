<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Services\Payment\LimitChecker;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class CreatePayment extends Command
{
	protected $signature = 'payment:create';

    public function handle(LimitChecker $limitChecker): void
	{
		$gateway = random_int(1, 2);
		if (!$limitChecker->isLimitOverflow($gateway, 1000000))
		{
			$amount = random_int(1, 1000000);

			Payment::query()
				->create([
					'merchant_invoice_id' => random_int(1, PHP_INT_MAX),
					'status_id' => Arr::random(Payment::STATUSES_LIST),
					'payment_gateway_id' => $gateway,
					'amount' => $amount,
					'amount_paid' => rand(0, 1) > 0.5 ? $amount : 0,
				]);
		}
	}
}
