<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "limit",
        "limit_used",
    ];

    /**
     * Increment daily limit
     */
    public function increaseDailyLimit(): int
    {
        return $this->increment("limit_used", 1);
    }

    public function isGonnaBeLocked(): bool
    {
        return $this->limit_used >= $this->limit;
    }

    /**
     * Reset daily limit
     */
    public function resetDailyLimit(): void
    {
        $this->update([
            "limit_used" => 0
        ]);
    }

    public function rules()
    {
        return $this->related::getValidationRules();
    }
}
