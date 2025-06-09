<?php

namespace Modules\Users\Actions\Auth;

use Modules\Users\Models\Customer;
use Modules\Users\Models\User;

class LoginUserAction
{
    public function handle(array $credentials): ?User
    {
        if (!Customer::attempt($credentials)) {
            return null;
        }

        /** @var User $user */
        $user = auth("customer")->user();
        $user = new Customer($user->toArray());

        $accessToken = $user->createToken("web")
            ->plainTextToken;

        $user->access_token = $accessToken;

        return $user;
    }
}
