<?php

namespace Modules\Orders\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Users\Transformers\CustomerResource;

class OrderStatusLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "order_id" => $this->order_id,
            "user_id" => $this->user_id,
            "type" => $this->type,
            "status" => $this->status,
            "notes" => $this->notes,
            "created_at" => $this->created_at,

            "order" => new OrderResource($this->whenLoaded("order")),
            "user" => new CustomerResource($this->whenLoaded("user")),
        ];
    }
}
