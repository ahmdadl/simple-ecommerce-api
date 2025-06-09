<?php

namespace Modules\Carts\ValueObjects;

use Modules\Carts\Models\Cart;
use Modules\Products\Models\Product;

final class CartTotals
{
    public function __construct(
        public float $original,
        public float $discount,
        public float $taxes,
        public int $products,
        public int $items,
        public float $subtotal,
        public float $total
    ) {}

    // Optional: Add validation logic
    public static function validate(array $data): void
    {
        if (!isset($data["original"]) || !is_numeric($data["original"])) {
            throw new \InvalidArgumentException(
                "Original must be a numeric value."
            );
        }
    }

    // Convert the object to an array
    public function toArray(): array
    {
        return [
            "original" => $this->original,
            "discount" => $this->discount,
            "taxes" => $this->taxes,
            "products" => $this->products,
            "items" => $this->items,
            "subtotal" => $this->subtotal,
            "total" => $this->total,
        ];
    }

    // Create a Totals object from an array
    public static function fromArray(array $data): self
    {
        self::validate($data);

        return new self(
            $data["original"],
            $data["discount"],
            $data["taxes"],
            $data["products"],
            $data["items"],
            $data["subtotal"],
            $data["total"]
        );
    }

    /**
     * generate default cart totals
     */
    public static function default(): self
    {
        return new self(
            original: 0,
            discount: 0,
            taxes: 0,
            products: 0,
            items: 0,
            subtotal: 0,
            total: 0
        );
    }

    /**
     * calculate totals from product
     */
    public static function calculateFromProduct(
        Product $product,
        int $quantity
    ): self {
        return new self(
            original: $product->price * $quantity,
            discount: $product->discountedPrice * $quantity,
            products: 1,
            items: $quantity,
            subtotal: $product->price * $quantity,
            taxes: ($product->sale_price * $quantity) / 1.15,
            total: $product->sale_price * $quantity
        );
    }

    /**
     * calculate totals from cart
     */
    public static function calculateFromCart(Cart $cart): self
    {
        $original = 0;
        $discount = 0;
        $products = 0;
        $items = 0;
        $taxes = 0;
        $subtotal = 0;
        $total = 0;

        foreach (
            $cart
                ->loadMissing(["shippingAddress"])
                ->items()
                ->get()
            as $item
        ) {
            $original += $item->totals->original;
            $discount += $item->totals->discount;
            $products += $item->totals->products;
            $items += $item->totals->items;
            $subtotal += $item->totals->subtotal;
            $taxes += $item->totals->taxes;
            $total += $item->totals->total;
        }

        return new self(
            original: $original,
            discount: $discount,
            products: $products,
            items: $items,
            subtotal: $subtotal,
            taxes: round($total - $taxes, 2),
            total: round($total)
        );
    }

    /**
     * turn to string
     * @return string
     */
    public function __toString(): string
    {
        return (string) json_encode($this->toArray());
    }
}
