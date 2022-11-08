<?php

namespace App\Events;

use App\Models\Invoice;
use App\Models\Wallet;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InvoiceWasUpdated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly Invoice $invoice
    ) {
    }
}
