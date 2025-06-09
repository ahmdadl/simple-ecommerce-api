<?php

namespace Modules\Carts\Casts;

use Modules\Carts\ValueObjects\CartTotals;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

/**
 * @implements CastsAttributes<CartTotals, CartTotals>
 */
class CartTotalsCast implements CastsAttributes
{
    // Convert the JSON from the database to a CartTotals object
    public function get(
        $model,
        string $key,
        $value,
        array $attributes
    ): CartTotals {
        // @phpstan-ignore-next-line
        if (!json_validate($value)) {
            throw new \Exception("Cart Totals value is invalid JSON format");
        }

        // @phpstan-ignore-next-line
        return CartTotals::fromArray(json_decode($value, true));
    }

    // Convert the CartTotals object to JSON for storage in the database
    public function set($model, string $key, $value, array $attributes): string
    {
        if (!$value instanceof CartTotals) {
            throw new InvalidArgumentException(
                "The provided value is not an instance of CartTotals."
            );
        }

        // @phpstan-ignore-next-line
        return json_encode($value->toArray());
    }
}
