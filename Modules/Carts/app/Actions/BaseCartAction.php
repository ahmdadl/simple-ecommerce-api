<?php

namespace Modules\Carts\Actions;

use Modules\Carts\Services\CartService;

abstract class BaseCartAction
{
    public function __construct(public readonly CartService $service)
    {
        //
    }

    /**
     * init new action
     */
    public static function new(): static
    {
        return new static(app(CartService::class));
    }
}
