<?php

namespace Modules\Carts\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Products\Transformers\ProductResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "product" => new ProductResource(
                $this->whenLoaded("product", $this->product)
            ),
            "quantity" => $this->quantity,
            "totals" => $this->totals,
        ];
    }
}
