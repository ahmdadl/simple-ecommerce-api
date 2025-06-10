<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Users\Models\User;

class UsersDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->admin()
            ->create([
                "name" => "Ahmed Admin",
                "email" => "admin@gmail.com",
                "password" => Hash::make("password"),
            ]);

        User::factory()
            ->customer()
            ->create([
                "name" => "Ahmed Customer",
                "email" => "customer@gmail.com",
                "password" => Hash::make("password"),
            ]);
    }
}
