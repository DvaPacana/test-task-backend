<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\InvoiceRepository;
use App\Repositories\InvoiceEloquentRepository;
use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(InvoiceRepository::class, function ($app) {
            return new InvoiceEloquentRepository();
        });
    }
}
