<?php

namespace Modules\Addresses\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Users\Models\User;

class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Addresses\Models\Address::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            "user_id" => fn() => User::factory()->customer(),
            "city_id" => fn() => \Modules\Cities\Models\City::factory(),
            "first_name" => fake()->name,
            "last_name" => fake()->name,
            "title" => fake()->sentence(1),
            "address" => fake()->address,
            "phone" => fakePhone(),
            "email" => fake()->unique()->email,
            "is_default" => false,
        ];
    }
}

