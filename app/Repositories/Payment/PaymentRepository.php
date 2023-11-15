<?php

namespace App\Repositories\Payment;

use App\Models\Payment;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PaymentRepository extends BaseRepository implements PaymentRepositoryContract
{
    public function __construct()
    {
        $class = Payment::class;
        parent::__construct($class);
    }

    public function create(array $data): Payment
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->find($id)->update($data);
    }

    public function find(int $id): ?Payment
    {
        return $this->model->find($id);
    }

    public function findOrFail(int $id): Payment
    {
        return $this->model->findOrFail($id);
    }

    public function updateOrFail(int $id, array $data)
    {
        $payment = $this->find($id);

        if($payment === null){
            throw new ModelNotFoundException("Payment not found");
        }

        return $payment->update($data);
    }

    public function findByGatewayAndMerchantId(string $gateway, int $merchantInvloiceId): ?Payment
    {
        return $this->model->where("payment_gateway", $gateway)->where("merchant_invoice_id", $merchantInvloiceId)->first();
    }

    public function findByGatewayAndMerchantOrFail(string $gateway, int $merchantInvloiceId): Payment
    {
        $payment = $this->findByGatewayAndMerchantId($gateway, $merchantInvloiceId);

        if($payment === null){
            throw new ModelNotFoundException("Payment not found");
        }

        return $payment;
    }
}