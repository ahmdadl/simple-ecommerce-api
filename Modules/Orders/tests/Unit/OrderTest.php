<?php

use Modules\Orders\Models\Order;
use Modules\Orders\Models\OrderAddress;
use Modules\Orders\Models\OrderCoupon;
use Modules\Payments\Models\PaymentAttempt;
use Modules\Payments\Models\PaymentMethod;
use Modules\Users\Models\User;

test("order_has_relations", function () {
    $order = Order::factory()->create();

    expect($order->user)
        ->toBeInstanceOf(User::class)
        ->and($order->shippingAddress)
        ->toBeInstanceOf(OrderAddress::class);
});
