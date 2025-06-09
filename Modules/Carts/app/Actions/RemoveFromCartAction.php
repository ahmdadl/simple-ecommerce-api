<?php

namespace Modules\Carts\Actions;

use Modules\Carts\Models\CartItem;
use Modules\Carts\Services\CartService;
use Modules\Core\Exceptions\ApiException;
use Modules\Products\Models\Product;

class RemoveFromCartAction
{
    public function __construct(public readonly CartService $cartService) {}

    public function handle(CartItem $cartItem): void
    {
        $this->cartService->removeItem($cartItem);
    }

    /**
     * use product to get cart item
     */
    public function usingProduct(Product $product): void
    {
        $product->lockForUpdate();

        $cartItem = $this->cartService->findCartItemByProduct($product);

        if (!$cartItem) {
            throw new ApiException(__("carts::t.product_not_found_in_cart"));
        }

        $this->handle($cartItem);
    }
}
