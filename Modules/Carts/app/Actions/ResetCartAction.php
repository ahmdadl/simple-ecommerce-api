<?php

namespace Modules\Carts\Actions;

use Modules\Carts\Services\CartService;

class ResetCartAction
{
    public function __construct(public readonly CartService $cartService) {}

    public function handle(): void
    {
        $this->cartService->reset();
    }
}
