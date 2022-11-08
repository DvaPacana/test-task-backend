<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\Merchant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        return [
            'amount' => $this->faker->numberBetween(100000, 500000),
            'status' => InvoiceStatus::CREATED->value,
            'user_id' => User::factory(),
            'merchant_id' => Merchant::factory(),
        ];
    }
}
