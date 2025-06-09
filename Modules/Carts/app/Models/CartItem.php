<?php

namespace Modules\Carts\Models;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Carts\Casts\CartTotalsCast;
use Modules\Carts\Database\Factories\CartItemFactory;
use Modules\Products\Models\Product;

#[UseFactory(CartItemFactory::class)]
class CartItem extends Model
{
    /** @use HasFactory<CartItemFactory> */
    use HasFactory, HasUlids;

    /**
     * cast fields
     */
    protected function casts(): array
    {
        return [
            "quantity" => "int",
            "totals" => CartTotalsCast::class,
        ];
    }

    /**
     * cart
     * @return BelongsTo<Cart, $this>
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * product
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
