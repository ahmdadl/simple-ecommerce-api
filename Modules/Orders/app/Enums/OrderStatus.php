<?php

namespace Modules\Orders\Enums;

enum OrderStatus: string
{
    use \Modules\Core\Traits\HasEnumHelpers;

    case PENDING = "pending";
    case PROCESSING = "processing";
    case SHIPPED = "shipped";
    case DELIVERED = "delivered";
    case CANCELLED = "cancelled";

    /**
     * get status color
     */
    public function color(): string
    {
        return match ($this) {
            self::PENDING => "warning",
            self::PROCESSING => "primary",
            self::SHIPPED => "success",
            self::DELIVERED => "success",
            self::CANCELLED => "danger",
        };
    }

    /**
     * get localized name
     */
    public function localized(): string
    {
        return __("orders::t.status.{$this->value}");
    }
}
