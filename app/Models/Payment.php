<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const NEW = "new";
    public const PENDING = "pending";
    public const COMPLETED = "done";
    public const EXPIRED = "expired";
    public const REJECTED = "rejected";

}
