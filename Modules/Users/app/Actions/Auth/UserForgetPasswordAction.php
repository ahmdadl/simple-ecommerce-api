<?php

namespace Modules\Users\Actions\Auth;

use Modules\Users\Models\Auth\PasswordResetToken;
use Modules\Users\Models\Customer;
use Modules\Users\Notifications\ForgetPasswordNotification;
use Modules\Users\Utils\UserUtils;

class UserForgetPasswordAction
{
    public function handle(string $email): bool
    {
        $user = Customer::role()->active()->whereEmail($email)->first();

        if (!$user) {
            return false;
        }

        $token = (string) UserUtils::generateToken();

        PasswordResetToken::whereEmail($email)->delete();
        PasswordResetToken::create([
            "email" => $email,
            "token" => $token,
            "created_at" => now(),
        ]);

        // Send the email
        rescue(fn() => $user->notify(new ForgetPasswordNotification($token)));

        return true;
    }
}
