<?php

namespace Modules\Orders\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Orders\Enums\OrderStatus;
use Modules\Orders\Enums\OrderStatusLogType;
use Modules\Orders\Models\Order;
use Modules\Users\Models\User;

class OrderStatusLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Orders\Models\OrderStatusLog::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            "order_id" => fn() => Order::factory(),
            "user_id" => fn() => User::factory(),
            "status" => fake()->randomElement(OrderStatus::cases())->value,
            "type" => fake()->randomElement(OrderStatusLogType::cases())->value,
            "notes" => fake()->boolean() ?: fake()->text(),
        ];
    }
}
