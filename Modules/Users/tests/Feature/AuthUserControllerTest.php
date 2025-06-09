<?php

use Modules\Users\Models\Auth\PasswordResetToken;
use Modules\Users\Models\Customer;
use Modules\Users\Models\User;
use Modules\Users\Notifications\ForgetPasswordNotification;
use Modules\Users\Notifications\NewCustomerNotification;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\postJson;
use function Pest\Laravel\travel;

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

test("user_cannot_reset_password_with_invalid_data", function () {
    postJson(route("api.auth.forget-password"), ["email" => fake()->email])
        ->assertStatus(400)
        ->assertSee(__("users::t.invalid_credentials"));
    postJson(
        route("api.auth.reset-password", [
            "email" => fake()->email,
            "password" => ($password = str()->random(8)),
            "password_confirmation" => $password,
            "otp" => str()->random(6),
        ])
    )
        ->assertStatus(400)
        ->assertSee(__("users::t.invalid_credentials"));

    $user = User::factory()->customer()->create();
    postJson(route("api.auth.forget-password"), [
        "email" => $user->email,
    ])->assertNoContent();

    // test invalid token
    postJson(
        route("api.auth.reset-password", [
            "email" => $user->email,
            "password" => ($password = str()->random(8)),
            "password_confirmation" => $password,
            "otp" => str()->random(6),
        ])
    )
        ->assertStatus(400)
        ->assertSee(__("users::t.invalid_token"));

    // test token expired
    $passwordReset = PasswordResetToken::where("email", $user->email)
        ->latest()
        ->first();

    travel(6)->minutes();

    postJson(
        route("api.auth.reset-password", [
            "email" => $user->email,
            "password" => ($password = str()->random(8)),
            "password_confirmation" => $password,
            "otp" => $passwordReset->token,
        ])
    )
        ->assertStatus(400)
        ->assertSee(__("users::t.token_expired"));
});

test("user_can_reset_password", function () {
    Notification::fake();
    Notification::assertNothingSent();

    $user = User::factory()->customer()->create();

    postJson(route("api.auth.forget-password"), [
        "email" => $user->email,
    ])->assertNoContent();

    Notification::assertSentTo(Customer::latest()->first(), ForgetPasswordNotification::class);

    $passwordReset = PasswordResetToken::firstWhere("email", $user->email);

    postJson(route("api.auth.reset-password"), [
        "email" => $user->email,
        "otp" => $passwordReset->token,
        "password" => ($password = str()->random(8)),
        "password_confirmation" => $password,
    ])->assertOk();

    expect(
        Customer::attempt(["email" => $user->email, "password" => $password])
    )->toBeTrue();

    assertDatabaseMissing("password_reset_tokens", ["email" => $user->email]);
});