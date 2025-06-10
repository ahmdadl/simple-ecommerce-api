<?php

use Illuminate\Support\Facades\Route;
use Modules\Orders\Http\Controllers\CreateGuestOrderController;
use Modules\Orders\Http\Controllers\CreateOrderController;
use Modules\Orders\Http\Controllers\OrdersController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
 */

Route::prefix("orders")
    ->name("orders.")
    ->group(function () {
        Route::middleware(["auth:customer"])->group(function () {
            Route::get("{order}", [OrdersController::class, "show"])->name(
                "show"
            );

            Route::post("", CreateOrderController::class)->name("store");
        });
    });
