<?php

use Illuminate\Support\Facades\Route;
use Modules\Addresses\Http\Controllers\AddressesController;

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

Route::apiResource("addresses", AddressesController::class)
    ->parameter("addresses", "myAddress")
    ->only(["index", "store", "update", "destroy"])
    ->middleware("auth:customer");
