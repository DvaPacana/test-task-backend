<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Currency;
use App\Models\Gateway;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(20)->create();
        Currency::factory(2)->create();

        $this->call([
            PaymentSeeder::class,
            GatewaySeeder::class
        ]);
    }
}
