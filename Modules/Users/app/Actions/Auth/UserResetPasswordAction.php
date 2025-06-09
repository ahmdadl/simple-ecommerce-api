<?php

namespace Modules\Users\Actions\Auth;

use Illuminate\Support\Facades\Hash;
use Modules\Core\Exceptions\ApiException;
use Modules\Users\Models\Auth\PasswordResetToken;
use Modules\Users\Models\Customer;
use Modules\Users\Models\User;
use Throwable;

class UserResetPasswordAction
{
    public function handle(array $data): Throwable|User
    {
        ["email" => $email, "password" => $password, "otp" => $token] = $data;

        $user = Customer::role()->active()->whereEmail($email)->first();

        if (!$user) {
            throw new ApiException(__("users::t.invalid_credentials"));
        }

        $passwordReset = PasswordResetToken::whereToken($token)
            ->whereEmail($email)
            ->first();

        if (!$passwordReset) {
            throw new ApiException(__("users::t.invalid_token"));
        }

        if ($passwordReset->created_at->addMinutes(5)->isPast()) {
            throw new ApiException(__("users::t.token_expired"));
        }

        $user->password = Hash::make($password);
        $user->save();

        $passwordReset->delete();

        // log user in
        auth("customer")->setUser($user);
        $accessToken = $user->createToken('web')->plainTextToken;
        $user->access_token = $accessToken;

        return $user;
    }
}
