<?php

use Illuminate\Support\Facades\Route;
use Modules\Uploads\Http\Controllers\UploadsController;

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

Route::get("/uploads/{upload}", [UploadsController::class, "show"])->name(
    "uploads.show"
);
