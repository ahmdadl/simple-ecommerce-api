<?php

use Modules\Carts\Models\Cart;
use Modules\Users\Models\User;

test("user_can_have_cart", function () {
    $user = User::factory()->customer()->create();

    $cart = Cart::factory()->for($user)->create();

    expect($cart->user)->toBeInstanceOf(User::class);
});
