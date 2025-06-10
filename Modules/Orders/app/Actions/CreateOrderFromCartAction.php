<?php

namespace Modules\Orders\Actions;

use Modules\Carts\Models\Cart;
use Modules\Core\Exceptions\ApiException;
use Modules\Orders\Enums\OrderPaymentStatus;
use Modules\Orders\Enums\OrderStatus;
use Modules\Orders\Models\Order;
use Modules\Orders\Models\OrderItemProduct;
use Modules\Payments\Enums\PaymentAttemptType;
use Modules\Payments\Models\PaymentAttempt;

class CreateOrderFromCartAction
{
    public function handle(
        Cart $cart,
        string $paymentMethod,
        ?string $receipt = null,
        OrderStatus $status = OrderStatus::PENDING
    ): ?Order {

        // create order address
        $orderShippingAddress = CreateOrderAddressAction::new()->handle(
            $cart->shippingAddress
        );

        // create order
        /** @var Order $order */
        $order = Order::create([
            "user_id" => user()?->id,
            "shipping_address_id" => $orderShippingAddress->id,
            "totals" => $cart->totals,
            "payment_method" => $paymentMethod,
        ]);

        // create order items
        foreach ($cart->items()->with("product")->get() as $item) {
            $orderItem = $order->items()->create([
                "product_id" => $item->product_id,
                "quantity" => $item->quantity,
                "totals" => $item->totals,
            ]);

            // create order item product
            OrderItemProduct::createFromProduct($orderItem->id, $item->product);
        }



        // set order status
        asUser(
            $order->user,
            fn() => ChangeOrderStatusAction::new()->handle(
                $order,
                OrderStatus::PENDING
            )
        );

        $cart->order_id = $order->id;
        $cart->save();

        return $order;
    }
}
