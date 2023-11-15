<?php

namespace App\Providers;

use App\Models\Payment;
use App\Repositories\Payment\PaymentRepository;
use App\Repositories\Payment\PaymentRepositoryContract;
use App\Repositories\PaymentGateway\PaymentGatewayRepository;
use App\Repositories\PaymentGateway\PaymentGatewayRepositoryContract;
use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentGatewayRepositoryContract::class, function($app){
            return $app->make(PaymentGatewayRepository::class);
        });

        $this->app->bind(PaymentRepositoryContract::class, function($app){
            return $app->make(PaymentRepository::class);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
