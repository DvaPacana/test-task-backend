<?php

namespace App\Repositories\Interfaces;

use App\Models\Payment;

interface PaymentRepositoryInterface
{
    function create(array $data): Payment;
    function update(int $id, array $data): bool;
    function updateOrFail(int $id, array $data);
    function find(int $id): ?Payment;
    function findOrFail(int $id): Payment;
    function findByGatewayAndMerchantId(int $gatewayId, int $merchantInvoiceId): ?Payment;
    function findByGatewayAndMerchantIdOrFail(int $gatewayId, int $merchantInvoiceId): Payment;
}
