<?php

namespace Modules\Core\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait HasSortOrderAttribute
{
    /**
     * Boot the trait.
     *
     * @return void
     */
    public static function bootHasSortOrderAttribute()
    {
        static::creating(function (Model $model) {
            if (empty($model->sort_order)) {
                /** @var int $maxSortOrder */
                $maxSortOrder = $model::max("sort_order");
                $model->sort_order = $maxSortOrder + 1;
            }
        });
    }

    /**
     * initialize trait
     *
     * @return void
     */
    public function initializeHasSortOrderAttribute()
    {
        $this->casts = array_merge($this->casts, ["sort_order" => "int"]);
    }

    /**
     * Scope a query order by sort_order
     *
     * @param  Builder<Model>  $query
     */
    public function scopeOrderBySortOrderAsc(Builder $query): void
    {
        $query->orderBy("sort_order", "asc");
    }

    /**
     * Scope a query order by sort_order
     *
     * @param  Builder<Model>  $query
     */
    public function scopeOrderBySortOrder(Builder $query): void
    {
        $query->orderBy("sort_order", "desc");
    }
}
