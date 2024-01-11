<?php

namespace Database\Seeders;

use App\Models\PaymentGateway;
use App\Payment\Concrete\PaymentGatewayOne;
use App\Payment\Concrete\PaymentGatewayTwo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentGateway::firstOrCreate(['name' => PaymentGatewayOne::getName()],[
            "name" => PaymentGatewayOne::getName(),
            "limit" => 100,
            "related" => PaymentGatewayOne::class
        ]);

        PaymentGateway::firstOrCreate(['name' => PaymentGatewayTwo::getName()],[
            "name" => PaymentGatewayTwo::getName(),
            "limit" => 100,
            "related" => PaymentGatewayTwo::class
        ]);
    }
}
