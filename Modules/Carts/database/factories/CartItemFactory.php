<?php

namespace Modules\Carts\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Carts\Models\Cart;
use Modules\Carts\ValueObjects\CartTotals;
use Modules\Products\Models\Product;

/**
 * @extends Factory<\Modules\Carts\Models\CartItem>
 *
 * @mixin Factory<\Modules\Carts\Models\CartItem>
 */
class CartItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Carts\Models\CartItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            "cart_id" => fn() => Cart::factory(),
            "product_id" => fn() => Product::factory(),
            "quantity" => fake()->numberBetween(1, 10),
            "totals" => CartTotals::default(),
        ];
    }
}
