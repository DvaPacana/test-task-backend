<?php

namespace Database\Seeders;

use App\Enums\PaymentStatuses;
use App\Models\Currency;
use App\Models\Payment;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $merchants = User::all();
        $currencies = Currency::all();
        $faker = Faker::create();
        foreach ($merchants as $merchant) {
            for ($i = 0; $i !== $currencies->count(); $i++) {
                $amount = $faker->numberBetween(10000, 66669999);
                $amount_paid = $faker->numberBetween(10000, $amount);
                Payment::create([
                    'merchant_id' => $merchant->id,
                    'currency_id' => $currencies->slice($i, 1)->first()->id,
                    'status' => Arr::random([PaymentStatuses::NEW, PaymentStatuses::PENDING]),
                    'amount' => $amount,
                    'amount_paid' => $amount_paid
                ]);
            }
        }
    }
}
