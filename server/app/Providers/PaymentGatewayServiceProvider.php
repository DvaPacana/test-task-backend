<?php

namespace App\Providers;

use App\Payment\Concrete\PaymentGatewayOne;
use App\Payment\Concrete\PaymentGatewayTwo;
use App\Payment\Contracts\PaymentGatewayInterface;
use App\Repositories\Interfaces\PaymentGatewayRepositoryInterface;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class PaymentGatewayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     * @throws BindingResolutionException
     */
    public function register(): void
    {
        $paymentGatewayRepository = $this->app->make(PaymentGatewayRepositoryInterface::class);

        $this->app->bind(PaymentGatewayInterface::class, function($app) use ($paymentGatewayRepository){
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
                throw new Exception("No key or id is set in config");
            }

            return new PaymentGatewayOne((int)$merchantId, $merchantKey, $app->make(PaymentRepositoryInterface::class));
        });

        $this->app->bind(PaymentGatewayTwo::class, function($app){
            $appId = config("payment_gateway_two.id");
            $appKey = config("payment_gateway_two.key");

            if($appId === null || $appKey === null){
                throw new Exception("No key or id is set in config");
            }

            return new PaymentGatewayTwo((int)$appId, $appKey, $app->make(PaymentRepositoryInterface::class));
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
