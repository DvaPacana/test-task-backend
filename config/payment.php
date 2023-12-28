<?php

return [
    'gateways' => [
        'goldenpay',
        'superpay',
    ],

    'rate_limiter_name' => 'payment:',

    'drivers' => [
        'goldenpay' => [
            /**
             * Сервис класс для работы с данными платежа.
             * Должен наследоваться от  \App\Http\Services\PaymentGateway\PaymentGateway::class
             *
             * @required
             * */
            'service' => \App\Http\Services\PaymentGateway\GoldenpayPaymentGateway::class,

            /**
             * FormRequest класс для валидации и проверки подписи.
             * Должен наследоваться от \App\Http\Requests\PaymentGateway\PaymentGatewayRequest::class
             *
             * @required
             * */
            'validation' => \App\Http\Requests\PaymentGateway\GoldenpayRequest::class,
            'key' => env('GOLDENPAY_KEY'),
            'hash_driver' => 'sha256',
            /**
             * Количество принимаемых платежей за день
             *
             * @optional
             * */
            'payments_limit' => 1,
        ],

        'superpay' => [
            'service' => \App\Http\Services\PaymentGateway\SuperpayPaymentGateway::class,
            'validation' => \App\Http\Requests\PaymentGateway\SuperpayRequest::class,
            'key' => env('SUPERPAY_KEY'),
            'hash_driver' => 'md5',
        ],
    ],
];
