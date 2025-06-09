<?php

use Illuminate\Support\Facades\Route;
use Modules\Carts\Http\Controllers\CartController;

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

Route::controller(CartController::class)
    ->prefix("cart")
    ->name("cart.")
    ->group(function () {
        Route::middleware(["auth:customer"])->group(function () {
            // address routes
            Route::patch("address/{address}", "applyShippingAddress")->name(
                "set-address"
            );
            // Route::delete("address", "removeShippingAddress")->name("remove-address");
            Route::post("address", "storeShippingAddress")->name(
                "create-address"
            );

            Route::get("", "index")->name("index");
            Route::post("{product}", "add")->name("add");

            Route::patch("{product}/by-product", "updateByProduct")->name(
                "update-by-product"
            );
            Route::patch("{cartItem}", "update")->name("update");

            Route::delete("{product}/by-product", "removeByProduct")->name(
                "remove-by-product"
            );
            Route::delete("{cartItem}", "remove")->name("remove");

            Route::delete("", "reset")->name("reset");
        });
    });
