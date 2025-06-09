<?php

namespace Modules\Brands\Models;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Modules\Brands\Database\Factories\BrandFactory;
use Modules\Core\Models\Scopes\HasActiveState;
use Modules\Products\Models\Product;

#[UseFactory(BrandFactory::class)]
class Brand extends Model
{
    /** @use HasFactory<BrandFactory> */
    use HasFactory, HasUlids,HasTranslations, HasActiveState, SoftDeletes;

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
    
    /**
     * products relation
     *
     * @return HasMany<Product, $this>
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }  
}
