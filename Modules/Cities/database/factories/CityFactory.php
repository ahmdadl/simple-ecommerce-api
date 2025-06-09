<?php

namespace Modules\Cities\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Cities\Models\City::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => [
                'en' => fake()->city(),
                'ar' => fake()->city(),
            ],
            'is_active' => true,
        ];
    }
}

