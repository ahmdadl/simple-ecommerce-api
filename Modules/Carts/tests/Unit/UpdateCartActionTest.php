<?php

use Modules\Carts\Actions\UpdateCartAction;
use Modules\Carts\Models\Cart;
use Modules\Carts\Models\CartItem;
use Modules\Carts\Services\CartService;
use Modules\Core\Exceptions\ApiException;
use Modules\Products\Models\Product;

test("update_cart_fails_if_quantity_less_than_one", function () {
    $cartService = new CartService(Cart::factory()->create());
    $action = new UpdateCartAction($cartService);

    $this->expectException(ApiException::class);
    $action->handle(
        $cartItem = CartItem::factory()->for($cartService->cart)->create(),
        -1
    );
});

test(
    "update_cart_fails_if_product_quantity_less_than_item_quantity",
    function () {
        $cartService = new CartService(Cart::factory()->create());
        $action = new UpdateCartAction($cartService);

        $product = Product::factory()->create(["stock" => 2]);

        $cartItem = CartItem::factory()
            ->for($cartService->cart)
            ->create([
                "product_id" => $product->id,
                "quantity" => 1,
            ]);

        $cartService->refresh();
        expect($cartService->cart->totals->items)->toBe(1);

        $this->expectException(ApiException::class);
        $action->handle($cartItem, 3);
    }
);

test("update_cart_success", function () {
    $cartService = new CartService(Cart::factory()->create());
    $cartItem = CartItem::factory()->for($cartService->cart)->create();
    $action = new UpdateCartAction($cartService);

    $action->handle($cartItem, 3);

    $cartService->refresh();
    expect($cartService->cart->totals->items)->toBe(3);
});

test("update_cart_using_product_fails_if_product_not_in_cart", function () {
    $cartService = new CartService(Cart::factory()->create());
    $action = new UpdateCartAction($cartService);

    $this->expectException(ApiException::class);
    $action->usingProduct(Product::factory()->create(), 3);
});

test("update_cart_success_using_product", function () {
    $cartService = new CartService(Cart::factory()->create());
    $product = CartItem::factory()->for($cartService->cart)->create()->product;
    $action = new UpdateCartAction($cartService);

    $action->usingProduct($product, 3);

    $cartService->refresh();
    expect($cartService->cart->totals->items)->toBe(3);
});
