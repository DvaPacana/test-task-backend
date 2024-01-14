<?php

namespace App\Services\Payment;

use App\Models\FirstPayment;
use App\Models\Payment;
use Illuminate\Support\Carbon;

class LimitChecker {
	/**
	 * Проверяет, достигнут ли лимит платежей для указанного шлюза.
	 *
	 * @param int $gatewayId ID платежного шлюза
	 * @param int $limit Установленный лимит для шлюза
	 *
	 * @return bool Возвращает true, если лимит достигнут, иначе false
	 */
	public function isLimitOverflow(int $gatewayId, int $limit): bool {
		return $$this->getTotalAmountForToday($gatewayId) > $limit;
	}

	/**
	 * Возвращает общую сумму платежей для указанного шлюза за текущий день.
	 *
	 * @param int $gatewayId ID платежного шлюза
	 *
	 * @return int Общая сумма платежей
	 */
	private function getTotalAmountForToday(int $gatewayId): int {
		return Payment::query()
			->where('payment_gateway_id', $gatewayId)
			->whereDate('created_at', Carbon::today())
			->sum('amount');
	}

}
