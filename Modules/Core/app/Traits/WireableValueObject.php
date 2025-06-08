<?php

namespace Modules\Core\Traits;

trait WireableValueObject
{
    /**
     * turn to livewire wireable
     */
    public function toLivewire(): array
    {
        return static::toArray();
    }

    /**
     * get from livewire wireable
     */
    public static function fromLivewire(mixed $value): static
    {
        // @phpstan-ignore-next-line
        return static::fromArray($value);
    }
}
