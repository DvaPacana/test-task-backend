<?php

namespace App\Payment\Contracts;

use Illuminate\Http\Request;

interface PaymentGatewayContract
{

    /**
     * Get the merchant key in payment gateway
     * 
     * @return string
     * 
     * @throws App\Payment\Exceptions\PaymentGatewayException
     */
    function getPaymentGatewayMerchantKey(): string;

    /**
     * Get the unique merchant identifier in payment gateway
     * 
     * @return int
     * 
     * @throws App\Payment\Exceptions\PaymentGatewayException
     */
    function getPaymentGatewayMerchantId(): int;

    /**
     * Handling payment gateway callback
     * 
     * @param array $data data from callback
     * @return bool
     * 
     * @throws App\Payment\Exceptions\PaymentGatewayException
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