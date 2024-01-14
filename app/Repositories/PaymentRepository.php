<?php

namespace App\Repositories;

use App\Models\FirstPayment;
use App\Models\Payment;
use App\Services\Payment\DTO\AbstarctCallbackDTO;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class PaymentRepository {
	/**
	 * Создает новый платеж в базе данных.
	 *
	 * @param array $data Данные для создания платежа
	 *
	 * @return Builder|Model Созданный объект платежа
	 */
	public function create(array $data): Builder|Model {
		return Payment::query()->create($data);
	}

	public function save(Payment $payment, AbstarctCallbackDTO $requestData): bool 
	{
		return $payment->update([
			'status' => $requestData->status(),
			'amount_paid' => $requestData->amountPaid()
		]);
	}

	/**
	 * Получает платеж по его ID.
	 *
	 * @param int $id ID платежа
	 *
	 * @return Builder|Builder[]|Collection|Model Объект платежа или null, если не найден
	 */
	public function findById(int $id): Builder|array|Collection|Model {
		return Payment::query()->find($id);
	}

	public function findByMerchantPaymentId(int $paymentId, int $gateway): Model|Builder {
		return Payment::query()
				->where('merchant_invoice_id', $paymentId)
				->where('payment_gateway_id', $gateway)
				->firstOrFail();
	}
	
	/**
	 * Получает все платежи.
	 *
	 * @return Collection Коллекция платежей
	 */
	public function getAll(): Collection {
		return Payment::all();
	}

}
