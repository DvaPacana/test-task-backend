<?php

namespace App\Repositories\Classes;

use App\Models\PaymentGateway;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\PaymentGatewayRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PaymentGatewayRepository extends BaseRepository implements PaymentGatewayRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(PaymentGateway::class);
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
