<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTO\InvoiceData;
use Illuminate\Http\Request;

interface Merchant
{
    public function rules(): array;
    public function getSignature(): string;
    public function checkSignature(string $apiKey, array $payload): bool;
    public function makeSignature(string $apiKey, array $payload): string;
    public function makeInvoiceData(array $payload): InvoiceData;
}
