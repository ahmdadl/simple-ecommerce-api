<?php

namespace Modules\Users\Actions\Profile;

use Illuminate\Support\Facades\Hash;
use Modules\Users\Models\User;

class UpdatePasswordAction
{
    /**
     * handle action
     */
    public function handle(array $data): void
    {
        /** @var User $user */
        $user = user();

        $user->update([
            "password" => Hash::make($data["password"]),
        ]);
    }
}
