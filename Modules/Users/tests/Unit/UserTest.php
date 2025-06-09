<?php

use Modules\Users\Enums\UserRole;
use Modules\Users\Models\Admin;
use Modules\Users\Models\Customer;
use Modules\Users\Models\User;

it("user_have_columns_by_default", function () {
    $userFactory = User::factory()->customer()->make();

    $user = Customer::create([
        ...$userFactory->only(["name", "email", "password"]),
    ]);

    expect($user->role)->toBe(UserRole::CUSTOMER);
    expect($user->is_active)->toBeTrue();
});

test("user_role_auth_works", function () {
    /** @var Customer $customer */
    $customer = User::factory()->customer()->create();

    expect($customer->role)->toBe(UserRole::CUSTOMER);

    $credentials = [
        "email" => $customer->email,
        "password" => "123123123",
    ];

    expect(Customer::attempt($credentials))->toBeTrue();
    expect(Admin::attempt($credentials))->toBeFalse();
});