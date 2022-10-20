<?php

namespace App\Console\Commands;

use App\Models\Gateway;
use Illuminate\Console\Command;

class ResetGatewayPaymentsCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gateways:reset_payments_count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset Gateways payments count daily';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Gateway::query()->update(['payment_count' => 0]);
    }
}
