<?php

namespace Modules\Orders\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Addresses\Models\Address;

class OrderAddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Orders\Models\OrderAddress::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $address = Address::factory()->create();

        return [
            "address_id" => $address->id,
            "user_id" => $address->user_id,
            "city_id" => $address->city_id,
            "city_title" => $address->city->title,
            "name" => $address->first_name . " " . $address->last_name,
            "title" => $address->title,
            "address" => $address->address,
            "phone" => $address->phone,
        ];
    }
}
