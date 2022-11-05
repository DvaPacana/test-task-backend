<?php

namespace App\Console\Commands;

use App\Actions\Users\UpdateBalance;
use App\Contracts\InvoiceRepository;
use App\DTO\WalletData;
use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\Wallet;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Money\Currency;
use Money\Money;

class HandleDeferredInvoice extends Command
{
    protected $signature = 'handle:invoice:deferred';
    protected $description = 'Handle invoice:deferred';

    public function __construct(
        private InvoiceRepository $repository
    ) {
        parent::__construct();
    }

    public function handle()
    {
        $this->repository->deferredHasExpired(function (Collection $collection) {
            $collection->map(function (Invoice $invoice) {
                DB::beginTransaction();

                try {
                    $user = $invoice->user()->first();
                    $walletData = new WalletData(
                        userId: $user->id,
                        amount: new Money(
                            amount: $invoice->amount,
                            currency: new Currency(Wallet::DEFAULT_CURRENCY->value)
                        ),
                    );

                    $wallet = UpdateBalance::execute($walletData);

                    $invoice->status = InvoiceStatus::COMPLETED;
                    $invoice->deferred_expires_at = null;
                    $invoice->save();

                    DB::commit();

                    $this->info(sprintf('update invoice: %s [balance]: %s', $invoice->id, $wallet->balance));
                }
                catch (\Exception $exception) {
                    DB::rollBack();
                    throw $exception;
                }
            });
        });

        return Command::SUCCESS;
    }
}
