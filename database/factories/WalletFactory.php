<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Currency;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

class WalletFactory extends Factory
{
    protected $model = Wallet::class;

    public function definition(): array
    {
        return [
            'balance' => $this->faker->numberBetween(100000, 500000),
            'currency' => $this->faker->currencyCode,
            'user_id' => User::factory(),
        ];
    }

    public function usd(): static
    {
        return $this->state(fn (array $attributes) => [
            'currency' => Currency::USD->value,
        ]);
    }
}
