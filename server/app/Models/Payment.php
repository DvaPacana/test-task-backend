<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const NEW = 1;

    public const COMPLETED = 2;
    public const PENDING = 3;
    public const EXPIRED = 4;
    public const REJECTED = 5;

}
