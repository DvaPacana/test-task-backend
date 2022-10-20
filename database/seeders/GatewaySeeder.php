<?php

namespace Database\Seeders;

use App\Enums\PaymentStatuses;
use App\Models\Currency;
use App\Models\Gateway;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class GatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencies = Currency::all();
        $faker = Faker::create();
        foreach ($currencies as $currency) {
            Gateway::create([
                'name' => $currency->name . ' ' . $currency->id,
                'currency_id' => $currency->id,
                'payment_status' => PaymentStatuses::COMPLETED->value,
                'payment_limit' => $faker->numberBetween(2, 6),
            ]);
        }
    }
}
