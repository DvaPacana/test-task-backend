<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

	public const STATUS_NEW = 1;
	public const STATUS_PENDING = 2;
	public const STATUS_COMPLETED = 3;
	public const STATUS_EXPIRED = 4;
	public const STATUS_REJECTED = 5;

	public const STATUSES_LIST = [
		self::STATUS_NEW,
		self::STATUS_PENDING,
		self::STATUS_COMPLETED,
		self::STATUS_EXPIRED,
		self::STATUS_REJECTED
	];

	public const PAYMENT_GATEWAY_FIRST = 1;
	public const PAYMENT_GATEWAY_SECOND = 2;

	protected $fillable = [
		'merchant_invoice_id',
		'status_id',
		'payment_gateway_id',
		'amount',
		'amount_paid',
	];
}
