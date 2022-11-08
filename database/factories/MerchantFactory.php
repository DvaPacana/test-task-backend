<?php

namespace Database\Factories;

use App\Models\Merchant;
use Illuminate\Database\Eloquent\Factories\Factory;

class MerchantFactory extends Factory
{
    protected $model = Merchant::class;

    public function definition(): array
    {
        return [
            'external_id' => $this->faker->unique()->numberBetween(10, 100000),
            'api_key' => $this->faker->md5,
            'daily_limit' => $this->faker->numberBetween(10, 100000),
        ];
    }
}
