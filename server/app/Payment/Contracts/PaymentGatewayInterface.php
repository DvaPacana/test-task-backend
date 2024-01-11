<?php

namespace App\Payment\Contracts;

use Illuminate\Http\Request;

interface PaymentGatewayInterface
{

    /**
     * Get the merchant key in payment gateway
     *
     * @return string
     *
     */
    function getMerchantKey(): string;

    /**
     * Get the unique merchant identifier in payment gateway
     *
     * @return int
     *
     */
    function getMerchantId(): int;

    /**
     * Handling payment gateway callback
     *
     * @param array $data data from callback
     * @return bool
     *
     */
    function processPayment(array $data): bool;

    /**
     * get validation rules
     */
    static function getValidationRules(): array;

    /**
     * extract data from request body
     */
    static function extractData(Request $request): array;

    /**
     * return payment gateway name
     */
    static function getName(): string;
}
