<?php

namespace Modules\Carts\Models;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Addresses\Models\Address;
use Modules\Carts\Casts\CartTotalsCast;
use Modules\Carts\Database\Factories\CartFactory;
use Modules\Users\Models\User;

#[UseFactory(CartFactory::class)]
class Cart extends Model
{
    /** @use HasFactory<CartFactory> */
    use HasFactory, HasUlids;

    /**
     * cast fields
     */
    protected function casts(): array
    {
        return [
            "totals" => CartTotalsCast::class,
        ];
    }

    /**
     * cart owner
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * cart items
     * @return HasMany<CartItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * shippingAddress
     * @return BelongsTo<Address, $this>
     */
    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }
}
