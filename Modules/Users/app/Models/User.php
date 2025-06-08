<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;
// use Modules\Users\Database\Factories\UserFactory;

#[UseFactory(UserFactory::class)]
class User extends Model
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasUlids;

    /**
     * cast fields
     */
    // protected function casts(): array
    // {
    //     return [];
    // }

    // 
}
