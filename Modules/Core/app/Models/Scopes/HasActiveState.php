<?php

namespace Modules\Core\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait HasActiveState
{
    /**
     * initialize trait
     */
    public function initializeHasActiveState(): void
    {
        $this->casts = array_merge($this->casts, ["is_active" => "boolean"]);
    }

    /**
     * Scope a query to only include is_active records.
     *
     * @param  Builder<Model>  $query
     */
    public function scopeActive(Builder $query): void
    {
        $query->where("is_active", 1);
    }

    /**
     * Scope a query to exclude is_active records.
     *
     * @param  Builder<Model>  $query
     */
    public function scopeNotActive(Builder $query): void
    {
        $query->where("is_active", 0);
    }
}
