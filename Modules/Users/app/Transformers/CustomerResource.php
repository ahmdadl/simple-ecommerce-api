<?php

namespace Modules\Users\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "phone" => $this->phone,
            "role" => $this->role,
            "cart_items_count" => $this->cartItemsCount,
            "access_token" => $this->when(
                !empty($this->access_token ?? ""),
                $this->access_token ?? ""
            ),
        ];
    }
}
