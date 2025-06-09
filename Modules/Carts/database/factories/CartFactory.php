<?php

namespace Modules\Carts\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Carts\ValueObjects\CartTotals;
use Modules\Users\Models\User;

/**
 * @extends Factory<\Modules\Carts\Models\Cart>
 *
 * @mixin Factory<\Modules\Carts\Models\Cart>
 */
class CartFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Carts\Models\Cart::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            "user_id" => fn() => User::factory()->customer(),
            "shipping_address_id" => null,
            "totals" => CartTotals::default(),
        ];
    }
}
