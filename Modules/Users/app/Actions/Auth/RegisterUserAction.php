<?php

namespace Modules\Users\Actions\Auth;

use Modules\Users\Enums\UserRole;
use Modules\Users\Models\Customer;
use Modules\Users\Models\User;
use Modules\Users\Notifications\NewCustomerNotification;

class RegisterUserAction
{
    /**
     * create user
     * @return array{User, string}
     */
    public function handle(array $data): array
    {
        $data["name"] = $data["first_name"] . " " . $data["last_name"];
        unset($data["first_name"], $data["last_name"]);

        $user = Customer::create([...$data, "role" => UserRole::CUSTOMER]);

        $access_token = $user->createToken("web")
            ->plainTextToken;


        rescue(fn() => $user->notify(new NewCustomerNotification()));

        return [$user, $access_token];
    }
}
