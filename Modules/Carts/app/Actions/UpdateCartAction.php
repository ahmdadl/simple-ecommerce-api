<?php

namespace Modules\Carts\Actions;

use Modules\Carts\Models\CartItem;
use Modules\Carts\Services\CartService;
use Modules\Core\Exceptions\ApiException;
use Modules\Products\Models\Product;

class UpdateCartAction
{
    public function __construct(public readonly CartService $cartService) {}

    public function handle(CartItem $cartItem, int $quantity): void
    {
        if ($quantity < 1) {
            throw new ApiException(
                __("carts::t.quantity_must_be_at_least_one")
            );
        }

        if ($cartItem->product?->stock < $quantity) {
            throw new ApiException(
                __("carts::t.product_stock_is_not_enough", [
                    "stock" => $cartItem->product?->stock,
                ])
            );
        }

        $this->cartService->updateItemQuantity($cartItem, $quantity);
    }

    /**
     * use product to get cart item
     */
    public function usingProduct(Product $product, int $quantity): void
    {
        $cartItem = $this->cartService->findCartItemByProduct($product);

        if (!$cartItem) {
            throw new ApiException(__("carts::t.product_not_found_in_cart"));
        }

        $this->handle($cartItem, $quantity);
    }
}
