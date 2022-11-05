<?php

declare(strict_types=1);

namespace App\Services\Merchants\Models;

use App\Contracts\Merchant;
use App\DTO\InvoiceData;
use App\Enums\InvoiceStatus;
use App\Models\Wallet;
use Illuminate\Support\Arr;
use Money\Currency;
use Money\Money;

final class BestPaymentMerchant extends AbstractMerchant implements Merchant
{
    public function checkSignature(string $apiKey, array $payload): bool
    {
        $payload = Arr::only($payload, array_keys($this->rules()));

        return $this->getSignature() === $this->makeSignature($apiKey, $payload);
    }

    public function makeSignature(string $apiKey, array $payload): string
    {
        ksort($payload);

        return md5(sprintf('%s%s', join('.', $payload), $apiKey));
    }

    public function makeInvoiceData(array $payload): InvoiceData
    {
        return new InvoiceData(
            id: (int) $payload['invoice'],
            status: InvoiceStatus::from(strtoupper($payload['status'])),
            amount: new Money($payload['amount_paid'], new Currency(
                Wallet::DEFAULT_CURRENCY->value
            )),
        );
    }

    public function rules(): array
    {
        return [
            'project' => ['required', 'integer', 'exists:merchants,external_id'],
            'invoice' => ['required', 'integer', 'exists:invoices,id'],
            'status' => ['required', 'string', sprintf('in:%s', mb_strtolower(join(',', InvoiceStatus::toArray())))],
            'amount' => ['required', 'integer'],
            'amount_paid' => ['required', 'integer'],
            'rand' => ['required', 'string'],
        ];
    }

    public function getSignature(): string
    {
        return $this->request->header('Authorization');
    }
}
