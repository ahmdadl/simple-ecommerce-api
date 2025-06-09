<?php

namespace Modules\Carts\Services;

use Illuminate\Support\Facades\DB;
use Modules\Addresses\Models\Address;
use Modules\Carts\Models\Cart;
use Modules\Carts\Models\CartItem;
use Modules\Carts\ValueObjects\CartTotals;
use Modules\Products\Models\Product;

final readonly class CartService
{
    /**
     * Initializes the CartService with a Cart instance and calls the init method.
     */
    public function __construct(public Cart $cart)
    {
        $this->init();
    }

    /**
     * Adds a specified quantity of a product to the cart, creating a new CartItem entry in the database.
     */
    public function addItem(Product $product, int $quantity = 1): void
    {
        DB::transaction(function () use ($product, $quantity) {
            $product->lockForUpdate();

            CartItem::create([
                "cart_id" => $this->cart->id,
                "product_id" => $product->id,
                "quantity" => $quantity,
                "totals" => CartTotals::calculateFromProduct(
                    $product,
                    $quantity
                ),
            ]);

            $this->removeAddons();

            $this->save();
        });
    }

    /**
     * Removes a specified CartItem from the cart.
     */
    public function removeItem(CartItem $cartItem): void
    {
        DB::transaction(function () use ($cartItem) {
            $this->cart->items()->where("id", $cartItem->id)->delete();

            $this->removeAddons();

            $this->save();
        });
    }

    /**
     * Updates the quantity of a specified CartItem in the cart.
     */
    public function updateItemQuantity(CartItem $cartItem, int $quantity): void
    {
        DB::transaction(function () use ($cartItem, $quantity) {
            $this->cart
                ->items()
                ->where("id", $cartItem->id)
                ->update([
                    "quantity" => $quantity,
                    "totals" => CartTotals::calculateFromProduct(
                        // @phpstan-ignore-next-line
                        $cartItem->product,
                        $quantity
                    ),
                ]);

            $this->removeAddons();

            $this->save();
        });
    }

    /**
     * Increases the quantity of a specified CartItem by one.
     */
    public function increaseItemQuantity(CartItem $cartItem): void
    {
        DB::transaction(function () use ($cartItem) {
            $this->cart
                ->items()
                ->where("id", $cartItem->id)
                ->increment("quantity");

            $this->removeAddons();

            $this->save();
        });
    }

    /**
     * Updates the quantity of a product in the cart based on the product ID.
     */
    public function updateProductQuantity(Product $product, int $quantity): void
    {
        DB::transaction(function () use ($product, $quantity) {
            $product->lockForUpdate();

            $this->cart
                ->items()
                ->where("product_id", $product->id)
                ->update([
                    "quantity" => $quantity,
                    "totals" => CartTotals::calculateFromProduct(
                        $product,
                        $quantity
                    ),
                ]);

            $this->removeAddons();

            $this->save();
        });
    }

    /**
     * set cart address
     */
    public function setShippingAddress(Address $address): void
    {
        DB::transaction(function () use ($address) {
            $this->cart->shippingAddress()->associate($address);

            $this->removeAddons();

            $this->save();
        });
    }

    /**
     * remove cart address
     */
    public function removeShippingAddress(): void
    {
        DB::transaction(function () {
            $this->cart->shippingAddress()->associate(null);

            $this->removeAddons();

            $this->save();
        });
    }

    /**
     * reset cart
     */
    public function reset(): void
    {
        DB::transaction(function () {
            $this->cart->items()->delete();

            $this->cart->setRelation("address", null);

            $this->save();
        });
    }

    /**
     * Saves the current state of the cart and calculates the totals.
     */
    public function save(): void
    {
        $this->calculateTotals();

        $this->cart->save();
    }

    /**
     * Refreshes the cart instance to reflect the latest data from the database.
     */
    public function refresh(): self
    {
        $this->cart->refresh();

        $this->loadItems();

        $this->calculateItemTotals();
        $this->calculateTotals();

        $this->cart->save();

        return $this;
    }

    /**
     * Checks if a specific product exists in the cart.
     */
    public function hasProduct(Product $product): bool
    {
        return $this->cart
            ->items()
            ->where("product_id", $product->id)
            ->exists();
    }

    /**
     * Finds and returns a CartItem associated with a specific product.
     */
    public function findCartItemByProduct(Product $product): ?CartItem
    {
        return $this->cart->items()->firstWhere("product_id", $product->id);
    }

    /**
     * delete cart and cart items
     */
    public function destroy(): void
    {
        DB::transaction(function () {
            $this->cart->items()->delete();

            $this->save();
        });
    }

    /**
     * remove any cart addons on any action
     */
    private function removeAddons(): void
    {
        // pass
    }

    /**
     * Loads the items in the cart along with their associated product details.
     */
    private function loadItems(): self
    {
        $this->cart->load([
            "items.product" => fn($q) => $q->select(
                "id",
                "price",
                "sale_price",
                "stock"
            ),
        ]);

        return $this;
    }

    /**
     * Calculates and updates the total amounts for each item in the cart.
     */
    private function calculateItemTotals(): self
    {
        // @phpstan-ignore-next-line
        $updatedItems = $this->cart
            ->loadMissing(["shippingAddress"])
            ->items->map(
                // @phpstan-ignore-next-line
                fn(CartItem $cartItem): array => [
                    "id" => $cartItem->id,
                    "cart_id" => $cartItem->cart_id,
                    "product_id" => $cartItem->product_id,
                    "quantity" => $cartItem->quantity,
                    "totals" => (string) CartTotals::calculateFromProduct(
                        // @phpstan-ignore-next-line
                        $cartItem->product,
                        $cartItem->quantity
                    ),
                ]
            );

        if ($updatedItems->isNotEmpty()) {
            CartItem::upsert(
                $updatedItems->toArray(),
                ["id"],
                ["totals", "quantity"]
            );
        }

        return $this;
    }

    /**
     * Calculates the overall totals for the cart.
     */
    private function calculateTotals(): self
    {
        $this->cart->totals = CartTotals::calculateFromCart($this->cart);

        return $this;
    }

    /**
     * Initializes the cart service, potentially performing setup tasks.
     */
    private function init(): void
    {
        // $this->save();
    }

    /**
     * get cart totals
     */
    public function getTotals(): CartTotals
    {
        return $this->cart->totals;
    }
}
