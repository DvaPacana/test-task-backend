<?php

use App\Http\Controllers\Api\V1\MerchantCallbackController;
use Illuminate\Support\Facades\Route;

Route::name('merchants:')
    ->prefix('merchants')
    ->group(function () {
        Route::post('callback/{merchant:external_id}', MerchantCallbackController::class)
            ->name('callback');
    });
