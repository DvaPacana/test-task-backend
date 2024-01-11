<?php

use App\Http\Controllers\PaymentGatewayController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'limit', 'prefix' => 'callback_url'],function(){
    Route::post("/{paymentGateway}", [PaymentGatewayController::class, "handle"]);
});
