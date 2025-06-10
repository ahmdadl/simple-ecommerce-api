<?php

namespace Modules\Orders\Models;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Addresses\Models\Address;
use Modules\Cities\Models\City;
use Modules\Orders\Database\Factories\OrderAddressFactory;
use Modules\Users\Models\User;
use Spatie\Translatable\HasTranslations;

#[UseFactory(OrderAddressFactory::class)]
class OrderAddress extends Model
{
    /** @use HasFactory<OrderAddressFactory> */
    use HasFactory, HasUlids, HasTranslations, SoftDeletes;

    protected array $translatable = ["city_title"];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<City, $this>
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * @return BelongsTo<Address, $this>
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    /**
     * create from normal address
     */
    public static function createFromAddress(Address $address): OrderAddress
    {
        return self::create([
            "address_id" => $address->id,
            "user_id" => $address->user_id,
            "city_id" => $address->city_id,
            "city_title" => $address->city?->title,
            "address" => $address->address,
            "phone" => $address->phone,
        ]);
    }
}
