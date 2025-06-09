<?php

namespace Modules\Categories\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Categories\Database\Factories\CategoryFactory;
use Modules\Core\Models\Scopes\HasActiveState;
use Spatie\LaravelPackageTools\Concerns\Package\HasTranslations;

#[UseFactory(CategoryFactory::class)]
class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory, HasUlids,
    HasActiveState,
    HasTranslations,
    Sluggable,
    SoftDeletes;

    public array $translatable = ["title", "description"];

    /**
     * cast fields
     */
    protected function casts(): array
    {
        return [
            'is_main' => 'boolean'
        ];
    }

     /**
     * get route key name
     */
    public function getRouteKeyName(): string
    {
        return "slug";
    }

    /**
     * sluggable source
     */
    public function sluggable(): array
    {
        return [
            "slug" => [
                "source" => "title",
            ],
        ];
    }
    
    // public function products(): HasMany
    // {
    //     return $this->hasMany(Product::class);
    // }   
}
