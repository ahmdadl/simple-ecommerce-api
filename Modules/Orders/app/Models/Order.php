<?php

namespace Modules\Orders\Models;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Carts\Casts\CartTotalsCast;
use Modules\Core\Models\Scopes\HasFiltersScope;
use Modules\Orders\Database\Factories\OrderFactory;
use Modules\Orders\Enums\OrderPaymentStatus;
use Modules\Orders\Enums\OrderStatus;
use Modules\Payments\Interfaces\Payable;
use Modules\Payments\Models\PaymentAttempt;
use Modules\Payments\Models\PaymentMethod;
use Modules\Users\Models\Customer;
use Modules\Users\Models\User;

#[UseFactory(OrderFactory::class)]
class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory, HasUlids, SoftDeletes;

    protected function casts(): array
    {
        return [
            "totals" => CartTotalsCast::class,
            "status" => OrderStatus::class,
        ];
    }

    /**
     * @return BelongsTo<Customer, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return BelongsTo<OrderAddress, $this>
     */
    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(OrderAddress::class);
    }

    /**
     * @return HasMany<OrderItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * @return HasMany<OrderStatusLog, $this>
     */
    public function statusLogs(): HasMany
    {
        return $this->hasMany(OrderStatusLog::class);
    }
}
