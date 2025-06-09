<?php

use Modules\Carts\Actions\RemoveFromCartAction;
use Modules\Carts\Models\Cart;
use Modules\Carts\Models\CartItem;
use Modules\Carts\Services\CartService;
use Modules\Products\Models\Product;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

test("remove_from_cart_fails_if_item_not_in_cart", function () {
    $cartService = new CartService(Cart::factory()->create());
    $action = new RemoveFromCartAction($cartService);

    $action->handle($cartItem = CartItem::factory()->create());

    assertDatabaseHas("cart_items", $cartItem->only(["id"]));
});

test("remove_from_cart_success", function () {
    $cartService = new CartService(Cart::factory()->create());
    $cartItem = CartItem::factory()->for($cartService->cart)->create();
    $action = new RemoveFromCartAction($cartService);

    $action->handle($cartItem);

    assertDatabaseMissing("cart_items", $cartItem->only(["id"]));
});

test("remove_from_cart_using_product_success", function () {
    $cartService = new CartService(Cart::factory()->create());
    $cartItem = CartItem::factory()->for($cartService->cart)->create();
    $action = new RemoveFromCartAction($cartService);

    $action->usingProduct($cartItem->product);

    assertDatabaseMissing("cart_items", $cartItem->only(["id"]));
});
