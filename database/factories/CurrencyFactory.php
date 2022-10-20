<?php

namespace Database\Factories;

use App\Enums\CurrencyNames;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Currency>
 */
class CurrencyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $name = $this->faker->unique()->randomElement(CurrencyNames::toArray());
        return [
            'name' => $name,
            'description' => $this->faker->paragraph
        ];
    }
}
