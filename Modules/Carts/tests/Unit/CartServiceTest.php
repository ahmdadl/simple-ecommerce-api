<?php

use Modules\Addresses\Models\Address;
use Modules\Carts\Models\Cart;
use Modules\Carts\Models\CartItem;
use Modules\Carts\Services\CartService;
use Modules\Cities\Models\City;
use Modules\Products\Models\Product;
use Modules\Users\Models\User;

test("cart_totals_updated_after_adding_item", function () {
    $cart = Cart::factory()->create();

    $cartService = new CartService($cart);

    $productA = Product::factory()->create([
        "price" => 100,
        "sale_price" => 50,
    ]);

    $productB = Product::factory()->create([
        "price" => 200,
        "sale_price" => 100,
    ]);

    $cartService->addItem($productA, 2);
    $cartService->addItem($productB);

    expect($cart->totals->original)->toBe((float) 400);
    expect($cart->totals->discount)->toBe((float) 200);
    expect($cart->totals->products)->toBe((int) 2);
    expect($cart->totals->items)->toBe((int) 3);
    expect($cart->totals->subtotal)->toBe((float) 400);
    expect($cart->totals->total)->toBe((float) 200);
});

it("cart_totals_updated_after_adding_items_to_old_cart", function () {
    $cart = Cart::factory()->create();

    $cartItems = CartItem::factory()->for($cart)->count(2)->create();

    $cartService = new CartService($cart);
    $cartService->refresh();

    expect($cart->totals->products)->toBe(2);
    $this->assertGreaterThanOrEqual(2, $cart->totals->items);

    $productA = Product::factory()->create([
        "price" => 100,
        "sale_price" => 50,
    ]);

    $cartService->addItem($productA);

    expect($cart->totals->products)->toBe(3);
    $this->assertGreaterThanOrEqual(3, $cart->totals->items);
});