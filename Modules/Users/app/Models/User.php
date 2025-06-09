<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Addresses\Models\Address;
use Modules\Carts\Models\Cart;
use Modules\Carts\Models\CartItem;
use Modules\Core\Models\Scopes\HasActiveState;
use Modules\Users\Database\Factories\UserFactory;
use Modules\Users\Enums\UserRole;

#[UseFactory(UserFactory::class)]
class User extends Authenticatable
{
    /** @use HasFactory<\Modules\Users\Database\Factories\UserFactory> */
    use HasActiveState,
        HasApiTokens,
        HasFactory,
        HasUlids,
        Notifiable,
        SoftDeletes;

    /**
     * current model role
     */
    public static ?UserRole $role = null;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = ["password", "remember_token"];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            "email_verified_at" => "datetime",
            "password" => "hashed",
            "role" => UserRole::class,
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (self $user) {
            if (!is_null(static::$role)) {
                $user->role = static::$role;
            }

            if (empty($user->is_active) && $user->is_active !== false) {
                $user->is_active = true;
            }
        });
    }

    /**
     * Scope a query to only include users with the specified role.
     *
     * @param  Builder<User>  $query
     */
    public function scopeRole(Builder $query, ?UserRole $userRole = null): void
    {
        $query->where("role", $userRole ?? static::$role?->value);
    }

    /**
     * Find a user by the given credentials.
     */
    public static function attempt(
        array $credentials,
        bool $remember = false
    ): bool {
        return auth()
            ->guard("web")
            ->attempt(
                array_merge($credentials, ["role" => static::$role?->value]),
                $remember
            );
    }

    /**
     * is customer
     * @return Attribute<boolean, void>
     */
    public function isCustomer(): Attribute
    {
        return Attribute::make(
            get: fn() => static::$role === UserRole::CUSTOMER
        )->shouldCache();
    }

    
    /**
     * cart items count
     * @return Attribute<int, void>
     */
    public function cartItemsCount(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->cartItems()->count()
        )->shouldCache();
    }


    /**
     * user cart
     * @return HasOne<Cart, $this>
     */
    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class, "user_id");
    }

    /**
     * user cart items
     * @return HasManyThrough<CartItem, Cart, $this>
     */
    public function cartItems(): HasManyThrough
    {
        return $this->hasManyThrough(
            CartItem::class,
            Cart::class,
            "user_id"
        );
    }

    /**
     * get user addresses
     * @return HasMany<Address, $this>
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class, "user_id");
    }
}
