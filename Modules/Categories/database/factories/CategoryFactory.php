<?php

namespace Modules\Categories\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Categories\Models\Category::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            "title" => [
                "en" => fake("ar")->sentence,
                "ar" => fake("ar")->sentence,
            ],
            "description" => [
                "en" => fake()->paragraph(),
                "ar" => fake("ar")->paragraph(),
            ],
            "is_main" => fake()->boolean(10),
            "is_active" => true,
        ];
    }
}

