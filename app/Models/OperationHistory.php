<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationHistory extends Model
{
    use HasFactory;

    protected $fillable = ['data', 'gateway_id'];

    public function getRouteKeyName()
    {
        return 'id';
    }
}
