<?php

namespace App\Providers;

use App\Payment\Concrete\PaymentGatewayOne;
use App\Payment\Concrete\PaymentGatewayTwo;
use App\Payment\Contracts\PaymentGatewayContract;
use App\Payment\Exceptions\PaymentGatewayException;
use App\Repositories\Payment\PaymentRepositoryContract;
use App\Repositories\PaymentGateway\PaymentGatewayRepositoryContract;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class PaymentGatewayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $paymentGatewayRepository = $this->app->make(PaymentGatewayRepositoryContract::class);

        $this->app->bind(PaymentGatewayContract::class, function($app) use ($paymentGatewayRepository){
            $request = $app->make(Request::class);

            $paymentGatewayName = $request->route('paymentGateway');
            $paymentGateway = $paymentGatewayRepository->findOrFail($paymentGatewayName);
            $relatedClass = $paymentGateway->related;

            if (!class_exists($relatedClass)) {
                throw new Exception("Invalid payment gateway.");
            }

            return $app->make($relatedClass);
        });

        $this->app->bind(PaymentGatewayOne::class, function($app){
            $merchantId = config("payment_gateway_one.id");
            $merchantKey = config("payment_gateway_one.key");

            if($merchantId === null || $merchantKey === null){
                throw new PaymentGatewayException("No key or id is set in config");
            }

            return new PaymentGatewayOne((int)$merchantId, $merchantKey, $app->make(PaymentRepositoryContract::class));
        });

        $this->app->bind(PaymentGatewayTwo::class, function($app){
            $appId = config("payment_gateway_two.id");
            $appKey = config("payment_gateway_two.key");

            if($appId === null || $appKey === null){
                throw new PaymentGatewayException("No key or id is set in config");
            }

            return new PaymentGatewayTwo((int)$appId, $appKey, $app->make(PaymentRepositoryContract::class));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
