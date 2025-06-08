<?php

namespace Modules\Core\Traits;

use BackedEnum;

trait HasEnumHelpers
{
    /**
     * The function `values` returns an array of values extracted from the "value" key of the cases
     * array.
     *
     * @return array<mixed> The `values()` method is returning an array of values extracted from the "value"
     *                      key of each element in the array returned by the `cases()` method.
     */
    public static function values(): array
    {
        return array_column(self::cases(), "value");
    }

    /**
     * The function `tryFromName` takes a string and returns a BackedEnum instance if a case with the
     * given name exists, or null otherwise.
     *
     * @param  string  $name  The name of the case to find.
     * @return static|null The function is returning either a static instance of the current class or null.
     */
    public static function tryFromName(string $name): ?static
    {
        return collect(static::cases())->first(
            fn(BackedEnum $case) => $case->name === $name
        );
    }
}
