<?php

use Modules\Carts\Actions\AddToCartAction;
use Modules\Carts\Models\Cart;
use Modules\Carts\Services\CartService;
use Modules\Core\Exceptions\ApiException;
use Modules\Products\Models\Product;

test("add_to_cart_fail_if_quantity_less_than_one", function () {
    $cartService = new CartService(Cart::factory()->create());
    $action = new AddToCartAction($cartService);

    $this->expectException(ApiException::class);

    $action->handle(Product::factory()->create(), 0);
});

test("add_to_cart_fail_if_product_out_of_stock", function () {
    $cartService = new CartService(Cart::factory()->create());
    $action = new AddToCartAction($cartService);

    $this->expectException(ApiException::class);

    $action->handle(Product::factory()->create(["stock" => 1]), 2);
});

test("add_to_cart_success", function () {
    $cartService = new CartService(Cart::factory()->create());
    $action = new AddToCartAction($cartService);

    $action->handle(
        Product::factory()->create([
            "stock" => 2,
            "price" => 400,
            "sale_price" => 100,
        ]),
        2
    );

    expect($cartService->cart->items()->count())->toBe(1);
    expect($cartService->cart->items()->first()->quantity)->toBe(2);
    expect($cartService->cart->totals->total)->toBe((float) 200);
    expect($cartService->cart->totals)
        ->items->toBe(2)
        ->products->toBe(1)
        ->original->toBe((float) 800)
        ->discount->toBe((float) 600);
});

test("add_to_cart_update_quantity_if_product_already_in_cart", function () {
    $cartService = new CartService(Cart::factory()->create());
    $action = new AddToCartAction($cartService);

    $action->handle(Product::factory()->create(["stock" => 50]));

    $action->handle($product = Product::factory()->create(["stock" => 50]));

    expect($cartService->cart->totals->products)->toBe(2);
    expect($cartService->cart->items()->count())->toBe(2);

    $action->handle($product, 2);

    expect($cartService->cart->totals->products)->toBe(2);
    expect($cartService->cart->totals->items)->toBe(3);
});

test("add_to_cart_success_even_with_20_products", function () {
    $cartService = new CartService(Cart::factory()->create());
    $action = new AddToCartAction($cartService);

    for ($i = 0; $i < 20; $i++) {
        $action->handle(Product::factory()->create(["stock" => 50]));
    }

    expect($cartService->cart->items()->count())->toBe(20);
    expect($cartService->cart->totals->products)->toBe(20);
    expect($cartService->cart->totals->items)->toBe(20);
});
