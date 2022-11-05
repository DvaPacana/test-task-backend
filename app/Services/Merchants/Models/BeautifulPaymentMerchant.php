<?php

declare(strict_types=1);

namespace App\Services\Merchants\Models;

use App\Contracts\Merchant;
use App\DTO\InvoiceData;
use App\Enums\InvoiceStatus;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Money\Currency;
use Money\Money;

final class BeautifulPaymentMerchant extends AbstractMerchant implements Merchant
{
    public function checkSignature(string $apiKey, array $payload): bool
    {
        $payload = Arr::only($payload, array_keys($this->rules()));

        return $this->getSignature() === $this->makeSignature($apiKey, $payload);
    }

    public function makeSignature(string $apiKey, array $payload): string
    {
        ksort($payload);
        $payload = join(':', Arr::except($payload, 'sign'));

        return hash('SHA256', sprintf('%s%s', $payload, $apiKey));
    }

    public function makeInvoiceData(array $payload): InvoiceData
    {
        return new InvoiceData(
            id: (int) $payload['payment_id'],
            status: InvoiceStatus::from(strtoupper($payload['status'])),
            amount: new Money($payload['amount_paid'], new Currency(
                Wallet::DEFAULT_CURRENCY->value
            )),
            createdAt: Carbon::parse($payload['timestamp'])
        );
    }

    public function rules(): array
    {
        return [
            'merchant_id' => ['required', 'integer', 'exists:merchants,external_id'],
            'payment_id' => ['required', 'integer', 'exists:invoices,id'],
            'status' => ['required', 'string', sprintf('in:%s', mb_strtolower(join(',', InvoiceStatus::toArray())))],
            'amount' => ['required', 'integer'],
            'amount_paid' => ['required', 'integer'],
            'timestamp' => ['required', 'integer'],
            'sign' => ['required', 'string'],
        ];
    }

    public function getSignature(): string
    {
        return $this->request->get('sign');
    }
}
