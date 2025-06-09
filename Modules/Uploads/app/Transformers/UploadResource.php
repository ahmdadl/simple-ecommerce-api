<?php

namespace Modules\Uploads\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UploadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "url" => $this->url,
            "type" => $this->type,
            "size" => $this->size,
            "created_at" => $this->created_at,
        ];
    }
}
