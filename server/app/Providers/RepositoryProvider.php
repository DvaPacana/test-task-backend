<?php

namespace App\Providers;

use App\Repositories\Classes\PaymentGatewayRepository;
use App\Repositories\Classes\PaymentRepository;
use App\Repositories\Interfaces\PaymentGatewayRepositoryInterface;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentRepositoryInterface::class, function($app){
            return $app->make(PaymentRepository::class);
        });

        $this->app->bind(PaymentGatewayRepositoryInterface::class, function($app){
            return $app->make(PaymentGatewayRepository::class);
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
