<?php

namespace Modules\Orders\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Addresses\Transformers\AddressResource;

class OrderAddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "address_id" => $this->address_id,
            "user_id" => $this->user_id,
            "city_id" => $this->city_id,
            "city_title" => $this->city_title,
            "name" => $this->name,
            "title" => $this->title,
            "address" => $this->address,
            "phone" => $this->phone,

            "addressRecord" => new AddressResource(
                $this->whenLoaded("address")
            ),
        ];
    }
}
