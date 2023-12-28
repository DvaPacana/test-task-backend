<?php

return [
    'gateways' => [
        'goldenpay',
        'superpay',
    ],

    'drivers' => [
        'goldenpay' => [
            'service' => \App\Http\Services\PaymentGateway\GoldenpayPaymentGateway::class,
            'validation' => \App\Http\Requests\PaymentGateway\GoldenpayRequest::class,
            'key' => env('GOLDENPAY_KEY'),
            'hash_driver' => 'sha256',
        ],

        'superpay' => [
            'service' => \App\Http\Services\PaymentGateway\SuperpayPaymentGateway::class,
            'validation' => \App\Http\Requests\PaymentGateway\SuperpayRequest::class,
            'key' => env('SUPERPAY_KEY'),
            'hash_driver' => 'md5',
        ],
    ],
];
