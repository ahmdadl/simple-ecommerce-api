<?php

namespace Modules\Orders\Models;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Carts\Casts\CartTotalsCast;
use Spatie\Translatable\HasTranslations;
use Modules\Orders\Database\Factories\OrderItemFactory;
use Modules\Products\Models\Product;

#[UseFactory(OrderItemFactory::class)]
class OrderItem extends Model
{
    /** @use HasFactory<OrderItemFactory> */
    use HasFactory, HasUlids, HasTranslations, SoftDeletes;

    protected array $translatable = ["title"];

    protected function casts(): array
    {
        return [
            "quantity" => "int",
            "totals" => CartTotalsCast::class,
        ];
    }

    /**
     * @return BelongsTo<Order, $this>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return BelongsTo<OrderItemProduct, $this>
     */
    public function product(): HasOne
    {
        return $this->hasOne(OrderItemProduct::class, "order_item_id");
    }
}
