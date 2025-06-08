<?php

namespace Modules\Users\Enums;

use Modules\Core\Traits\HasEnumHelpers;

enum UserRole: string
{
    use HasEnumHelpers;

    case ADMIN = "admin";
    case CUSTOMER = "customer";
}
