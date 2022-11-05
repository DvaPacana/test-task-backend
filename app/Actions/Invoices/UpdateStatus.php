<?php

declare(strict_types=1);

namespace App\Actions\Invoices;

use App\Actions\Users\UpdateBalance;
use App\DTO\InvoiceData;
use App\DTO\WalletData;
use App\Enums\InvoiceStatus;
use App\Events\InvoiceWasUpdated;
use App\Models\Invoice;
use App\Models\Merchant;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

final class UpdateStatus
{
    public static function execute(InvoiceData $data): Invoice
    {
        $invoice = Invoice::findOrFail($data->id);

        /** @var Merchant $merchant */
        $merchant = $invoice->merchant()->first();
        $availableLimit = $merchant->availableLimit() - $data->amount->getAmount();
        $currentStatus = $availableLimit > 0
            ? InvoiceStatus::COMPLETED
            : InvoiceStatus::DEFERRED;

        DB::beginTransaction();

        try {
            $user = $invoice->user()->first();

            if ($currentStatus->value == InvoiceStatus::COMPLETED->value) {
                $wallet = UpdateBalance::execute(
                    data: new WalletData(
                        userId: $user->id,
                        amount: $data->amount
                    )
                );
            }

            /** @var Invoice $invoice */
            $invoice = tap($invoice)->update([
                'status' => $currentStatus,
                'amount' => (int) $data->amount->getAmount(),
                'deferred_expires_at' => $currentStatus->value == InvoiceStatus::DEFERRED->value
                    ? Carbon::now()->addDay()
                    : null
            ]);

            InvoiceWasUpdated::dispatchIf($invoice->wasChanged('status'), $invoice);

            DB::commit();
        }
        catch (\Exception $exception) {
            DB::rollBack();

            throw $exception;
        }

        return $invoice;
    }
}
