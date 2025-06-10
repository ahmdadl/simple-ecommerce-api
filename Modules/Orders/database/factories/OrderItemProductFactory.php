<?php

namespace Modules\Orders\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Orders\Models\OrderItem;

class OrderItemProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Orders\Models\OrderItemProduct::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            "order_item_id" => fn() => OrderItem::factory(),
            "product_id" => fn() => null,
            "category_id" => fn() => null,
            "brand_id" => fn() => null,
            "title" => [
                "en" => fake()->sentence(),
                "ar" => fake()->sentence(),
            ],
            "category_title" => [
                "en" => fake()->sentence(),
                "ar" => fake()->sentence(),
            ],
            "brand_title" => [
                "en" => fake()->sentence(),
                "ar" => fake()->sentence(),
            ],
            "images" => [
                "https://picsum.photos/seed/" .
                fake()->numberBetween(1, 100) .
                "/200/300",
            ],
            "price" => fake()->randomFloat(2, 0, 100),
            "sale_price" => fake()->randomFloat(2, 0, 100),
            "sku" => fake()->sentence(),
        ];
    }
}
