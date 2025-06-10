<?php

namespace Modules\Orders\Models;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Modules\Orders\Database\Factories\OrderStatusLogFactory;
use Modules\Orders\Enums\OrderPaymentStatus;
use Modules\Orders\Enums\OrderStatus;
use Modules\Orders\Enums\OrderStatusLogType;
use Modules\Users\Models\User;

#[UseFactory(OrderStatusLogFactory::class)]
class OrderStatusLog extends Model
{
    /** @use HasFactory<OrderStatusLogFactory> */
    use HasFactory, HasUlids, SoftDeletes;

    /**
     * cast fields
     */
    protected function casts(): array
    {
        return [
            // "status" => OrderStatus::class,
            "type" => OrderStatusLogType::class,
        ];
    }

    /**
     * get status based on type
     * @return Attribute<string, void>
     */
    public function status(): Attribute
    {
        return Attribute::make(
            fn() => $this->attributes["type"] ===
            OrderStatusLogType::ORDER->value
                ? OrderStatus::from($this->attributes["status"])
                : OrderPaymentStatus::from($this->attributes["status"])
        );
    }

    /**
     * order
     * @return BelongsTo<Order, $this>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * user
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
