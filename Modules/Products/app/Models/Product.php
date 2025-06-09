<?php

namespace Modules\Products\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Modules\Brands\Models\Brand;
use Modules\Categories\Models\Category;
use Modules\Core\Models\Scopes\HasActiveState;
use Modules\Products\Database\Factories\ProductFactory;
use Spatie\Translatable\HasTranslations;

#[UseFactory(ProductFactory::class)]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory,
        HasUlids,
        HasActiveState,
        HasTranslations,
        Sluggable,
        SoftDeletes;

    /**
     * translatable fields
     *
     * @var array<int, string>
     */
    public array $translatable = ["title", "description"];

    /**
     * {@inheritDoc}
     */
    public static function boot(): void
    {
        parent::boot();

        static::creating(function (Product $product) {
            if (empty($product->sale_price) || $product->sale_price <= 0) {
                $product->sale_price = $product->price;
            }
        });
    }

    /**
     * Return the sluggable configuration array for this model.
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
     * cast fields
     */
    protected function casts(): array
    {
        return [
            "is_main" => "boolean",
            "price" => "float",
            "sale_price" => "float",
            'images' => 'array'
        ];
    }

    /**
     * product with discount
     * @param  Builder<Product>  $query
     */
    #[Scope]
    public function hasDiscount(Builder $query): void
    {
        $query
            ->whereNotNull("sale_price")
            ->whereColumn("sale_price", "<", "price");
    }

    /**
     * product has discount
     *
     * @return Attribute<float, void>
     */
    public function isDiscounted(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->sale_price < $this->price
        )->shouldCache();
    }

    /**
     * product discounted price
     *
     * @return Attribute<float, void>
     */
    public function discountedPrice(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->sale_price < $this->price
                ? round($this->price - $this->sale_price, 2)
                : 0.0
        )->shouldCache();
    }

    /**
     * product has stock
     *
     * @return Attribute<int, void>
     */
    public function hasStock(): Attribute
    {
        return Attribute::make(get: fn() => $this->stock > 0);
    }

    /**
     * product discounted percentage
     *
     * @return Attribute<float, void>
     */
    public function discountedPercentage(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->sale_price < $this->price
                ? round(
                    (($this->price - $this->sale_price) / $this->price) * 100
                )
                : 0.0
        )->shouldCache();
    }

    /** Relations */

    /**
     * parent category
     *
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * parent brand
     *
     * @return BelongsTo<Brand, $this>
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(related: Brand::class);
    }
}
