<?php

use App\Http\Controllers\API\PaymentGatewayController;
use App\Http\Middleware\EnsureDailyPaymentGatewayLimit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(EnsureDailyPaymentGatewayLimit::class)->group(function(){
    Route::post("/callback_url/{paymentGateway}", [PaymentGatewayController::class, "handleCallback"]);
});