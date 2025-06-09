<?php

namespace Modules\Users\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Users\Enums\UserRole;
use Modules\Users\Models\User;

/**
 * @extends Factory<User>
 *
 * @mixin Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = User::class;

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => fake()->name(),
            "email" => fake()->unique()->safeEmail(),
            "phone" => "201034" . random_int(100000, 999999),
            "email_verified_at" => now(),
            "password" => (static::$password ??= Hash::make("123123123")),
            "remember_token" => Str::random(10),
            "role" => UserRole::CUSTOMER,
            "is_active" => true,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(
            fn(array $attributes) => [
                "email_verified_at" => null,
            ]
        );
    }

    /**
     * Indicate that the model's active state.
     */
    public function unActive(): static
    {
        return $this->state(
            fn(array $attributes) => [
                "is_active" => false,
            ]
        );
    }

    /**
     * Indicate that the model's role.
     */
    public function role(UserRole $userRole): static
    {
        return $this->state(
            fn(array $attributes) => [
                "role" => $userRole,
            ]
        );
    }

    /**
     * Indicate that the model's role is admin.
     */
    public function admin(): static
    {
        return $this->state(
            fn(array $attributes) => [
                "role" => UserRole::ADMIN,
            ]
        );
    }

    /**
     * Indicate that the model's role is admin.
     */
    public function customer(): static
    {
        return $this->state(
            fn(array $attributes) => [
                "role" => UserRole::CUSTOMER,
            ]
        );
    }
}
