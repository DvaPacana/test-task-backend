<?php

namespace App\Repositories\PaymentGateway;

use App\Models\Payment;
use App\Models\PaymentGateway;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PaymentGatewayRepository extends BaseRepository implements PaymentGatewayRepositoryContract
{
    public function __construct()
    {
        $class = PaymentGateway::class;
        parent::__construct($class);
    }

    public function findOrFail(string $name): PaymentGateway
    {
        $model = $this->model->where("name", $name)->first();
        if($model === null){
            throw new ModelNotFoundException("Payment Gateway not found");
        }

        return $model;
    }
}