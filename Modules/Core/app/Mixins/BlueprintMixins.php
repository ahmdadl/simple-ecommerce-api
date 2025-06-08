<?php

namespace Modules\Core\Mixins;

final class BlueprintMixins
{
    /**
     * Add an 'is_active' boolean column to indicate active state.
     *
     * @return \Closure
     */
    public function activeState()
    {
        return fn() => $this->boolean("is_active")->default(true)->index();
    }

    /**
     * Add a 'sort_order' integer column with default value 1.
     *
     * @return \Closure
     */
    public function sortOrder()
    {
        return fn() => $this->integer("sort_order", false)->default(1);
    }

    /**
     * Add a 'id' column as ulid
     *
     * @return \Closure
     */
    public function uid()
    {
        return fn() => $this->ulid("id")->unique()->primary();
    }
}
