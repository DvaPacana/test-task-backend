<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\InvoiceRepository;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

final class InvoiceEloquentRepository implements InvoiceRepository
{
    public function deferredHasExpired(callable $callback): bool
    {
        return Invoice::query()
            ->where('deferred_expires_at', '<', Carbon::now())
            ->chunk(10, function (Collection $collection) use ($callback) {
                $callback($collection);
            });
    }
}
