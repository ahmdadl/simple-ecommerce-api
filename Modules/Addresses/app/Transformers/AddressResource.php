<?php

namespace Modules\Addresses\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Cities\Transformers\CityResource;
use Modules\Governments\Transformers\GovernmentResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "user_id" => $this->user_id,
            "city_id" => $this->city_id,
            "name" => $this->name,
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "title" => $this->title,
            "address" => $this->address,
            "email" => $this->email,
            "phone" => $this->phone,
            "is_default" => $this->is_default,
            "city" => new CityResource($this->whenLoaded("city")),
        ];
    }
}
