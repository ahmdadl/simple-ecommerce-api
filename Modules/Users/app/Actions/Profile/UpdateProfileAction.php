<?php

namespace Modules\Users\Actions\Profile;

use Modules\Users\Models\User;

class UpdateProfileAction
{
    public function handle(array $data): User
    {
        /**
         * @var User $user
         */
        $user = auth("customer")->user();

        $user->update($data);

        return $user->refresh();
    }
}
