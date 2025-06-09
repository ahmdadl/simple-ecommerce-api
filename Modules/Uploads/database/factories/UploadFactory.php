<?php

namespace Modules\Uploads\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UploadFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Uploads\Models\Upload::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            "name" => fake()->word . ".txt",
            "path" => "uploads/" . fake()->uuid . ".txt",
            "type" => "text/plain",
            "size" => fake()->numberBetween(100, 10000),
        ];
    }
}
