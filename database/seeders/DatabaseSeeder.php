<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Payment;
use App\Models\PaymentGateway;
use App\Payment\Concrete\PaymentGatewayOne;
use App\Payment\Concrete\PaymentGatewayTwo;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        PaymentGateway::create([
            "name" => PaymentGatewayOne::getName(),
            "limit" => 100,
            "related" => PaymentGatewayOne::class
        ]);

        PaymentGateway::create([
            "name" => PaymentGatewayTwo::getName(),
            "limit" => 100,
            "related" => PaymentGatewayTwo::class
        ]);
    }
}
