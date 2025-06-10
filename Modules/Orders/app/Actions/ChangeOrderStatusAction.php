<?php

namespace Modules\Orders\Actions;

use Modules\Core\Exceptions\ApiException;
use Modules\Core\Traits\HasActionHelpers;
use Modules\Orders\Enums\OrderStatus;
use Modules\Orders\Enums\OrderStatusLogType;
use Modules\Orders\Models\Order;
use Modules\Orders\Notifications\OrderStatusUpdatedNotification;

class ChangeOrderStatusAction
{
    use HasActionHelpers;

    public function handle(
        Order $order,
        OrderStatus $orderStatus,
        ?string $notes = null
    ) {
        $currentStatus = $order->status;

        if ($orderStatus === $currentStatus) {
            throw new ApiException(
                "Order is already in {$orderStatus->value} status"
            );
        }

        $order->status = $orderStatus;
        $order->save();

        $order->statusLogs()->create([
            "status" => $orderStatus,
            "type" => OrderStatusLogType::ORDER,
            "user_id" => $order->user->id,
            "notes" => $notes,
        ]);

        rescue(
            fn() => $order->user->notify(
                new OrderStatusUpdatedNotification($order)
            )
        );
    }
}
