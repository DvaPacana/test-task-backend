<?php

namespace App\Providers;

use App\Http\Requests\PaymentGateway\PaymentGatewayRequest;
use App\Http\Services\PaymentGateway\PaymentGateway;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PaymentGatewayServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        /*
         * NotFoundHttpException плохой выбор, но лень было делать отдельные эксепшены.
         * */

        $this->app->bind(PaymentGateway::class, function (Application $application){
            $gateway = request()->route('gateway');

            if(!$this->hasFormRequest($gateway)){
                throw new NotFoundHttpException();
            }

            return $application->make(config('payment.drivers.' . $gateway . '.service'));
        });

        $this->app->bind(PaymentGatewayRequest::class, function (Application $application){
            $gateway = request()->route('gateway');

            if(!$this->hasService($gateway)){
                throw new NotFoundHttpException();
            }

            return $application->make(config('payment.drivers.' . $gateway . '.validation'));
        });
    }

    protected function hasFormRequest(string $gateway)
    {
        $hasDriver = in_array($gateway, config('payment.gateways'));
        $class = config('payment.drivers.' . $gateway . '.validation');

        return $hasDriver && class_exists($class);
    }

    protected function hasService(string $gateway)
    {
        $hasDriver = in_array($gateway, config('payment.gateways'));
        $class = config('payment.drivers.' . $gateway . '.service');

        return $hasDriver && class_exists($class);
    }

    public function boot(): void
    {
    }
}
