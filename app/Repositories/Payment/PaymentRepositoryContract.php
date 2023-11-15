<?php

namespace App\Repositories\Payment;

use App\Models\Payment;

interface PaymentRepositoryContract
{
    function create(array $data): Payment;
    function update(int $id, array $data): bool;
    function updateOrFail(int $id, array $data);
    function find(int $id): ?Payment;
    function findOrFail(int $id): Payment;
    function findByGatewayAndMerchantId(string $gateway, int $merchantInvloiceId): ?Payment;
    function findByGatewayAndMerchantOrFail(string $gateway, int $merchantInvloiceId): Payment;
}