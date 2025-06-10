<?php

namespace Modules\Orders\Models;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Modules\Orders\Database\Factories\OrderItemProductFactory;
use Modules\Products\Models\Product;
use Modules\Uploads\Casts\UploadableMultiplePathsCast;

#[UseFactory(OrderItemProductFactory::class)]
class OrderItemProduct extends Model
{
    /** @use HasFactory<OrderItemProductFactory> */
    use HasFactory, HasUlids, HasTranslations, SoftDeletes;

    public array $translatable = ["title", "category_title", "brand_title"];

    /**
     * cast fields
     */
    protected function casts(): array
    {
        return [
            "images" => UploadableMultiplePathsCast::class,
        ];
    }

    /**
     * order item
     * @return BelongsTo<OrderItem, $this>
     */
    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    /**
     * product
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * create from product for order item
     */
    public static function createFromProduct(
        string $orderItemId,
        Product $product
    ): self {
        $product->loadMissing(["category", "brand"]);

        return self::factory()->create([
            "order_item_id" => $orderItemId,
            "product_id" => $product->id,
            "category_id" => $product->category_id,
            "brand_id" => $product->brand_id,
            "title" => $product->getTranslations("title"),
            "category_title" => $product->category->getTranslations("title"),
            "brand_title" => $product->brand->getTranslations("title"),
            "images" => $product->images,
            "price" => $product->price,
            "sale_price" => $product->sale_price,
            "sku" => $product->sku,
        ]);
    }
}
