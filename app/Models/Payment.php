<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'merchant_id',
        'payment_id',
        'status',
        'amount',
        'amount_paid',
        'signature',
    ];
}
