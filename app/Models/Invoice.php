<?php

declare(strict_types=1);

namespace App\Models;

use App\DTO\InvoiceData;
use App\Models\Casts\InvoiceStatusCast;
use App\Models\Casts\UuidCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'status',
        'deferred_expires_at'
    ];

    protected $casts = [
        'status' => InvoiceStatusCast::class
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    public function toDataObject(): InvoiceData
    {
        return InvoiceData::makeFromModel($this);
    }
}
