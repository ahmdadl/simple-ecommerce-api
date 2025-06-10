<?php

use Modules\Orders\Enums\OrderStatus;
use Modules\Orders\Enums\OrderStatusLogType;
use Modules\Orders\Models\OrderStatusLog;

use function Pest\Laravel\assertDatabaseHas;

test("status_log_has_type", function () {
    OrderStatusLog::factory()->create([
        "type" => OrderStatusLogType::ORDER,
    ]);

    assertDatabaseHas("order_status_logs", [
        "type" => OrderStatusLogType::ORDER->value,
    ]);
});

test("status_log_has_status_based_on_type", function () {
    $statusLog = OrderStatusLog::factory()->create([
        "type" => OrderStatusLogType::ORDER,
    ]);

    expect($statusLog->status)->toBeInstanceOf(OrderStatus::class);
});
