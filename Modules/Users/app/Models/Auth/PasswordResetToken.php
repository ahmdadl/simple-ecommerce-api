<?php

namespace Modules\Users\Models\Auth;

use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    protected $table = "password_reset_tokens";

    public $timestamps = false;

    public $primaryKey = "email";

    /**
     * cast attributes
     */
    protected function casts(): array
    {
        return [
            "created_at" => "datetime",
        ];
    }
}
