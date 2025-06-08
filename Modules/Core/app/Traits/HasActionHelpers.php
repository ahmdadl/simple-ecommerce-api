<?php

namespace Modules\Core\Traits;

trait HasActionHelpers
{
    /**
     * get dependency injection instance
     */
    public static function new(): static
    {
        return app(static::class);
    }
}
