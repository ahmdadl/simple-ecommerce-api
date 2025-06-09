<?php

namespace Modules\Users\Utils;

class UserUtils
{
    public static function generateToken(): int
    {
        return random_int(100000, 999999);
    }
}
