<?php

namespace Modules\Orders\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Payments\Transformers\PaymentAttemptResource;

class OrderResource extends JsonResource
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
            "status" => __("orders::t.status." . $this->status->value),
            "payment_method" => $this->payment_method,
            "totals" => $this->totals,
            "created_at" => $this->created_at,
            "user" => $this->whenLoaded("user"),
            "items" => OrderItemResource::collection(
                $this->whenLoaded("items")
            ),
            "shippingAddress" => new OrderAddressResource(
                $this->whenLoaded("shippingAddress")
            ),
        ];
    }
}
