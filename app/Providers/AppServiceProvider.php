<?php

namespace App\Providers;

use App\Services\Merchants\MerchantManager;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MerchantManager::class, function ($app) {
            return new MerchantManager(
                request: $app->get(Request::class)
            );
        });
    }

    public function boot(): void
    {
        // ..
    }
}
