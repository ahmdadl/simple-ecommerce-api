<?php

namespace Modules\Uploads\Models;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Uploads\Database\Factories\UploadFactory;

#[UseFactory(UploadFactory::class)]
class Upload extends Model
{
    /** @use HasFactory<UploadFactory> */
    use HasUlids, HasFactory;

    /**
     * get public url for file
     * @return Attribute<string, void>
     */
    public function url(): Attribute
    {
        return Attribute::make(
            // @phpstan-ignore-next-line
            get: fn() => config("app.url") . "/uploads/" . $this->id
        )->shouldCache();
    }
}
