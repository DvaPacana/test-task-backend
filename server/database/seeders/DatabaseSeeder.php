<?php

namespace Database\Seeders;


use App\Models\Payment;
use App\Models\PaymentGateway;
use App\Payment\Concrete\PaymentGatewayOne;
use App\Payment\Concrete\PaymentGatewayTwo;
use Illuminate\Database\Seeder;
use PhpParser\Node\Expr\Yield_;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
           CurrencySeeder::class,
           DictionaryStatusesSeeder::class,
           PaymentGatewaySeeder::class
        ]);
    }
}
