<?php

namespace Modules\Carts\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Addresses\Transformers\AddressResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "user_id" => $this->user_id,
            "shipping_address_id" => $this->shipping_address_id,
            "totals" => $this->totals,
            "shipping_address" => new AddressResource(
                $this->whenLoaded("shippingAddress", $this->shippingAddress)
            ),
            "items" => CartItemResource::collection(
                $this->whenLoaded(
                    "items",
                    CartItemResource::collection($this->items)
                )
            ),
        ];
    }
}
