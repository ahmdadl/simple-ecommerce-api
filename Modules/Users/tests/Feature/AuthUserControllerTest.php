<?php

use Modules\Users\Models\Customer;
use Modules\Users\Models\User;
use Modules\Users\Notifications\NewCustomerNotification;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

test("guest_cannot_login_with_invalid_credentials", function () {

    postJson(route("api.auth.login"), [
        "email" => "test",
        "password" => "test",
    ])
        ->assertJsonValidationErrorFor("email")
        ->assertJsonValidationErrorFor("password");
});

test("guest_can_login_with_valid_credentials", function () {

    $user = User::factory()->customer()->create();

    $response = postJson(route("api.auth.login"), [
        "email" => $user->email,
        "password" => ($password = "123123123"),
        "password_confirmation" => $password,
    ])->assertOk();

    $response
        ->assertSee("name")
        ->assertSee("email")
        ->assertSee("access_token");
});

test("user_cannot_login", function () {
    $user = User::factory()->customer()->create();

    actingAs($user, "customer");

    postJson(route("api.auth.login"), [
        "email" => $user->email,
        "password" => "123123123",
        "password_confirmation" => "123123123",
    ])->assertForbidden();
});

test("only_guest_can_register", function () {
    postJson(route("api.auth.register"))->assertStatus(422);

    $user = User::factory()->customer()->create();

    actingAs($user, "customer");

    postJson(route("api.auth.register"))->assertForbidden();
});

test("guest_cannot_register_with_invalid_data", function () {
      postJson(route("api.auth.register"))
        ->assertJsonValidationErrorFor("first_name")
        ->assertJsonValidationErrorFor("last_name")
        ->assertJsonValidationErrorFor("email")
        ->assertJsonValidationErrorFor("phone")
        ->assertJsonValidationErrorFor("password");
});

test("guest_can_register_with_valid_data", function () {

    Notification::fake();

    Notification::assertNothingSent();

    Notification::assertCount(0);

    postJson(route("api.auth.register"), [
        "first_name" => "John",
        "last_name" => "Doe",
        "email" => fake()->unique()->safeEmail,
        "phone" => "01143456576",
        "password" => ($password = "123123123"),
        "password_confirmation" => $password,
    ])
        ->assertOk()
        ->assertSee("access_token");

    Notification::assertCount(1);
    Notification::assertSentTo(Customer::latest()->first(), NewCustomerNotification::class);
});