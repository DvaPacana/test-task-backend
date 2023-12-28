<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//
//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('/payment/{gateway}', [\App\Http\Controllers\PaymentController::class, 'process'])
    ->name('payment.process')
    ->middleware('throttle:payment')
    ->whereIn('gateway', config('payment.gateways'));
