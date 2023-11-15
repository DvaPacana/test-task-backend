<?php

namespace App\Http\Middleware;

use App\Repositories\PaymentGateway\PaymentGatewayRepositoryContract;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDailyPaymentGatewayLimit
{
    public function __construct(
        private PaymentGatewayRepositoryContract $paymentGatewayRepository
    ){}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /**
         * @var \App\Models\PaymentGateway
         */
        $paymentGatewayName = $request->route("paymentGateway");
        $paymentGateway = $this->paymentGatewayRepository->findOrFail($paymentGatewayName);


        if($paymentGateway->isDailyLimitReached()){
            abort(412, "Too many request");
        }

        $paymentGateway->incrementDailyLimit();

        return $next($request);
    }
}
