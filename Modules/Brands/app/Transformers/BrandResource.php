<?php

namespace Modules\Brands\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Products\Transformers\ProductResource;

class BrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $image = "https://picsum.photos/seed/$this->id/600/600";

        return [
            "id" => $this->id,
            "title" => $this->title,
            "description" => $this->description,
            "slug" => $this->slug,
            "image" => $image,
            "is_main" => $this->is_main,

            // "products_count" => $this->whenCounted("products"),
            // "products" => ProductResource::collection(
            //     $this->whenLoaded("products")
            // ),
        ];
    }
}
