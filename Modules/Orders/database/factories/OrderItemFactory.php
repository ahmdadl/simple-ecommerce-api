<?php

namespace Modules\Orders\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Carts\ValueObjects\CartTotals;
use Modules\Orders\Models\Order;
use Modules\Products\Models\Product;

class OrderItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Orders\Models\OrderItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $product = Product::factory()->create();

        return [
            "order_id" => fn() => Order::factory(),
            "product_id" => fn() => $product->id,
            "product_title" => $product->title,
            "product_sku" => $product->sku,
            "quantity" => fake()->numberBetween(1, 10),
            "totals" => CartTotals::default(),
        ];
    }
}
