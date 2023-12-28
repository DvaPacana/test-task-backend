<?php

return [
    'gateways' => [
        'goldenpay',
    ],

    'drivers' => [
        'goldenpay' => [
            'service' => \App\Http\Services\PaymentGateway\GoldenpayPaymentGateway::class,
            'validation' => \App\Http\Requests\PaymentGateway\GoldenpayRequest::class,
            'key' => env('GOLDENPAY_KEY'),
            'hash_driver' => 'sha256',
        ],
    ],
];
