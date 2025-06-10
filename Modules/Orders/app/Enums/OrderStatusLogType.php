<?php

namespace Modules\Orders\Enums;

enum OrderStatusLogType: string
{
    use \Modules\Core\Traits\HasEnumHelpers;

    case ORDER = "order";
    case PAYMENT = "payment";

    /**
     * get status color
     */
    public function color(): string
    {
        return match ($this) {
            self::ORDER => "warning",
            self::PAYMENT => "danger",
        };
    }
}
