<?php

namespace Modules\Orders\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Products\Transformers\ProductResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "order_id" => $this->order_id,
            "quantity" => $this->quantity,
            "totals" => $this->totals,
            "created_at" => $this->created_at,
            "product" => new OrderItemProductResource(
                $this->whenLoaded("product", $this->product)
            ),
        ];
    }
}
