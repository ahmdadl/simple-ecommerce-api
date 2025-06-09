<?php

namespace Modules\Products\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Brands\Transformers\BrandResource;
use Modules\Categories\Transformers\CategoryResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        // @phpstan-ignore-next-line
        $images = collect($this->images)->map("uploads_url")->values();

        $images = [];
        for ($i = 0; $i < 4; $i++) {
            $images[] = "https://picsum.photos/seed/$this->id.$i/600/600";
        }

        return [
            "id" => $this->id,
            "category_id" => $this->category_id,
            "brand_id" => $this->brand_id,
            "title" => $this->title,
            "description" => $this->description,
            "slug" => $this->slug,
            "is_main" => $this->is_main,
            "images" => $images,
            "price" => $this->price,
            "sale_price" => $this->sale_price,
            "is_discounted" => $this->isDiscounted,
            "discounted_price" => $this->discountedPrice,
            "discounted_percentage" => $this->discountedPercentage,
            "stock" => $this->stock,
            "has_stock" => $this->hasStock,
            "sku" => $this->sku,
            "is_new" => $this->created_at->isCurrentWeek(), // used only here

            "category" => new CategoryResource($this->whenLoaded("category")),
            "brand" => new BrandResource($this->whenLoaded("brand")),
        ];
    }
}
