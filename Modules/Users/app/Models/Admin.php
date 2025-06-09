<?php

namespace Modules\Users\Models;

use Modules\Users\Enums\UserRole;

class Admin extends User
{
    protected $table = "users";

    /**
     * current model role
     */
    public static ?UserRole $role = UserRole::ADMIN;
}
