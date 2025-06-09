<?php

namespace Modules\Carts\Actions;

use Modules\Carts\Services\CartService;
use Modules\Core\Exceptions\ApiException;
use Modules\Products\Models\Product;

final class AddToCartAction
{
    public function __construct(public readonly CartService $cartService) {}

    public function handle(Product $product, int $quantity = 1): void
    {
        if ($quantity < 1) {
            throw new ApiException(
                __("carts::t.quantity_must_be_at_least_one")
            );
        }

        if ($product->stock < $quantity) {
            throw new ApiException(
                __("carts::t.product_stock_is_not_enough", [
                    "stock" => $product->stock,
                ])
            );
        }

        $cartItem = $this->cartService->findCartItemByProduct($product);

        if ($cartItem) {
            $this->cartService->updateProductQuantity($product, $quantity);

            return;
        }

        $this->cartService->addItem($product, $quantity);
    }
}
