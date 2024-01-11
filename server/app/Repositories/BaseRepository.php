<?php

namespace App\Repositories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    protected Model $model;

    public function __construct(string $class)
    {
        $this->model = new $class();
    }
}