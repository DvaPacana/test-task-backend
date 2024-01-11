<?php

namespace App\Repositories\Classes;

use App\Models\Payment;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
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

    public function updateOrFail(int $id, array $data): bool
    {
        $payment = $this->find($id);

        if($payment === null){
            throw new ModelNotFoundException("Payment not found");
        }

        return $payment->update($data);
    }

    public function findByGatewayAndMerchantId(int $gatewayId, int $merchantInvoiceId): ?Payment
    {
        return $this->model->where("payment_gateway_id", $gatewayId)->where("merchant_invoice_id", $merchantInvoiceId)->first();
    }

    public function findByGatewayAndMerchantIdOrFail(int $gatewayId, int $merchantInvoiceId): Payment
    {
        $payment = $this->findByGatewayAndMerchantId($gatewayId, $merchantInvoiceId);

        if($payment === null){
            throw new ModelNotFoundException("Payment not found");
        }

        return $payment;
    }
}
