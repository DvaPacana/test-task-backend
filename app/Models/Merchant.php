<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\InvoiceStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Merchant extends Model
{
    use HasFactory;

    protected $casts = [
        'rules' => 'array'
    ];

    protected $fillable = [
        'external_id',
        'helper_class',
        'api_key',
        'daily_limit',
        'rules'
    ];

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function availableLimit(): int
    {
        $sum = $this->invoices()
            ->where('created_at', '>', Carbon::now()->subDay())
            ->where('status', InvoiceStatus::COMPLETED)
            ->sum('amount')
        ;

        return ($this->daily_limit - $sum) >= 0
            ? $this->daily_limit - $sum
            : 0;
    }

    public function hasAvailableLimit(): bool
    {
        return $this->availableLimit() > 0;
    }
}
