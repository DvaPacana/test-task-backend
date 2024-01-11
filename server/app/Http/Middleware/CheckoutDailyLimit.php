<?php

namespace App\Http\Middleware;

use App\Models\PaymentGateway;
use App\Repositories\Interfaces\PaymentGatewayRepositoryInterface;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckoutDailyLimit
{
    public function __construct(
        private readonly PaymentGatewayRepositoryInterface $paymentGatewayRepository
    ){}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /**
         * @var PaymentGateway $paymentGatewayName
         */
        $paymentGatewayName = $request->route("paymentGateway");
        $paymentGateway = $this->paymentGatewayRepository->findOrFail($paymentGatewayName);


        if($paymentGateway->isGonnaBeLocked()){
            abort(412, "Too many request");
        }

        $paymentGateway->increaseDailyLimit();

        return $next($request);
    }
}
