<?php

namespace Modules\Orders\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Carts\ValueObjects\CartTotals;
use Modules\Orders\Enums\OrderStatus;
use Modules\Orders\Models\OrderAddress;
use Modules\Users\Models\User;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Orders\Models\Order::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            "user_id" => fn() => User::factory()->customer(),
            "shipping_address_id" => fn() => OrderAddress::factory(),
            "totals" => CartTotals::default(),
            "status" => OrderStatus::PENDING,
        ];
    }
}
