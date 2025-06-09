<?php

use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Addresses\Models\Address;
use Modules\Products\Models\Product;
use Modules\Users\Models\User;

use function Pest\Laravel\actingAs;

test("user_can_add_product_to_cart", function () {
    $product = Product::factory()->create(["sale_price" => 300]);
    /** @var Authenticatable $user */
    $user = User::factory()->create();

    $response = actingAs($user)->postJson(route("api.cart.add", [$product]));

    $response = $response->assertOk()->json()["data"];

    expect($response["cart"]["totals"]["total"])->toBe(300);
});

test("user_adding_twice_to_cart_will_update_quantity", function () {
    $product = Product::factory()->create(["sale_price" => 300]);
    /** @var Authenticatable $user */
    $user = User::factory()->create();

    $response = actingAs($user)->postJson(route("api.cart.add", [$product]));
    $response = actingAs($user)->postJson(route("api.cart.add", [$product]), [
        "quantity" => 2,
    ]);

    $response = $response->assertOk()->json()["data"];

    expect($response["cart"]["totals"]["total"])->toBe(600);
});

test("user_can_get_cart", function () {
    $product = Product::factory()->create(["sale_price" => 300]);
    /** @var Authenticatable $user */
    $user = User::factory()->create();

    actingAs($user)->postJson(route("api.cart.add", [$product]));

    $response = actingAs($user)
        ->getJson(route("api.cart.index"))
        ->assertOk()
        ->json()["data"];

    expect($response["cart"]["totals"]["total"])->toBe(300);
});

test("user_can_remove_product_from_cart", function () {
    $product = Product::factory()->create(["sale_price" => 300]);
    /** @var Authenticatable $user */
    $user = User::factory()->create();

    actingAs($user)->postJson(route("api.cart.add", [$product]));

    $response = actingAs($user)
        ->deleteJson(route("api.cart.remove-by-product", [$product]))
        ->assertOk()
        ->json()["data"];

    expect($response["cart"]["totals"]["total"])->toBe(0);
});

test("user_can_update_product_quantity_in_cart", function () {
    $product = Product::factory()->create(["sale_price" => 300]);
    /** @var Authenticatable $user */
    $user = User::factory()->create();

    actingAs($user)->postJson(route("api.cart.add", [$product]));

    $response = actingAs($user)
        ->patchJson(route("api.cart.update-by-product", [$product]), [
            "quantity" => 2,
        ])
        ->assertOk()
        ->json()["data"];

    expect($response["cart"]["totals"]["total"])->toBe(600);
});

test("cart_address_can_be_set_only", function () {
    $product = Product::factory()->create(["sale_price" => 300]);
    /** @var Authenticatable $user */
    $user = User::factory()->create();

    actingAs($user)->postJson(route("api.cart.add", [$product]));

    $address = Address::factory()->create();

    $response = actingAs($user)
        ->patchJson(route("api.cart.set-address", [$address]))
        ->assertOk()
        ->json();

    expect($response["data"]["cart"]["totals"]["total"])->toBe(300);
});

test("cart_automatically_selects_default_address", function () {
    $product = Product::factory()->create(["sale_price" => 300]);
    /** @var Authenticatable $user */
    $user = User::factory()->create();

    // Create a default address for the user
    $defaultAddress = Address::factory()
        ->create([
            "user_id" => $user->id,
            "is_default" => true,
        ]);

    actingAs($user)
        ->getJson(route("api.cart.index"), [
            "params" => [
                "with" => ["addresses"],
            ],
        ])
        ->assertOk()
        ->assertSee($defaultAddress->id);
});

test("cart_automatically_selects_first_address_when_no_default", function () {
    $product = Product::factory()->create(["sale_price" => 300]);
    /** @var Authenticatable $user */
    $user = User::factory()->create();

    // Create a non-default address for the user
    $firstAddress = Address::factory()
        ->create([
            "user_id" => $user->id,
            "is_default" => false,
        ]);

    actingAs($user)
        ->getJson(route("api.cart.index"))
        ->assertOk()
        ->assertSee($firstAddress->id);
});

test("can_create_cart_address", function () {
    asCustomer()
        ->postJson(
            route("api.cart.create-address", []),
            $address = Address::factory()->raw()
        )
        ->assertOk()
        ->assertSee($address["first_name"]);
});
