<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Http\Controllers\AuthUserController;
use Modules\Users\Http\Controllers\UserProfileController;
use Modules\Users\Http\Controllers\UsersController;

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
// guest
Route::controller(AuthUserController::class)
    ->name("auth.")
    ->group(function () {
        Route::post("login", "login")->name("login");
        Route::post("register", "register")->name("register");

        Route::post("forget-password", "forgetPassword")->name(
            "forget-password"
        );
        Route::post("reset-password", "resetPassword")->name("reset-password");
    });

    Route::middleware(["auth:customer"])
    ->name("profile.")
    ->prefix("profile")
    ->group(function () {
        Route::controller(UserProfileController::class)->group(function () {
            Route::patch("update", "updateProfile")->name("updateProfile");
            Route::patch("change-password", "updatePassword")->name(
                "changePassword"
            );
        });
    });