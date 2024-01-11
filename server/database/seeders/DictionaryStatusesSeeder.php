<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DictionaryStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            "NEW",
            "COMPLETED",
            "PENDING",
            "REJECTED",
            "EXPIRED"
        ];

        foreach($statuses as $status) {
            DB::table('dictionary_statuses')->insert(
                ['status' => $status]
            );
        }

    }
}
