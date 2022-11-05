<?php

declare(strict_types=1);

namespace App\Contracts;

interface InvoiceRepository
{
    public function deferredHasExpired(callable $callback): bool;
}
