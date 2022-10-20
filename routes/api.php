<?php

use App\Http\Controllers\Api\V1\CurrencyController;
use App\Http\Controllers\Api\V1\GatewayController;
use App\Http\Controllers\Api\V1\OperationHistoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// api/v1
Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1'], function () {
    Route::apiResource('gateways', GatewayController::class);
    Route::post('/gateways/{gateway_id}/update_payment_status', [GatewayController::class, 'updatePaymentStatus']);
    Route::post('/operation_history/{gateway_id}', [OperationHistoryController::class, 'store']);
    Route::get('/operation_history/{operation_history}', [OperationHistoryController::class, 'show']);
    Route::get('/operation_history', [OperationHistoryController::class, 'index']);
    Route::apiResource('currency', CurrencyController::class);
});
