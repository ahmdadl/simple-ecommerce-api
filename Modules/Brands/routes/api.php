<?php

use Illuminate\Support\Facades\Route;
use Modules\Brands\Http\Controllers\BrandsController;

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

Route::apiResource("brands", BrandsController::class)
    ->parameters([
        "brands" => "activeBrand",
    ])
    ->only(["index", "show"]);
