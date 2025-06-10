<?php

namespace Modules\Orders\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Brands\Transformers\BrandResource;
use Modules\Categories\Transformers\CategoryResource;
use Modules\Products\Transformers\ProductResource;

class OrderItemProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $images = [
            "https://picsum.photos/seed/" . $this->id . "/200/300",
            "https://picsum.photos/seed/" . $this->id . "/200/300",
            "https://picsum.photos/seed/" . $this->id . "/200/300",
        ];

        return [
            "id" => $this->id,
            "order_item_id" => $this->order_item_id,
            "product_id" => $this->product_id,
            "category_id" => $this->category_id,
            "brand_id" => $this->brand_id,

            "title" => $this->title,
            "category_title" => $this->category_title,
            "brand_title" => $this->brand_title,

            "images" => $images,
            "price" => $this->price,
            "sale_price" => $this->sale_price,
            "sku" => $this->sku,

            "product" => new ProductResource($this->whenLoaded("product")),
            "category" => new CategoryResource($this->whenLoaded("category")),
            "brand" => new BrandResource($this->whenLoaded("brand")),
        ];
    }
}
