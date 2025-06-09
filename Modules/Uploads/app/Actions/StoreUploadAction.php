<?php

namespace Modules\Uploads\Actions;

use Illuminate\Http\UploadedFile;
use Modules\Core\Traits\HasActionHelpers;
use Modules\Uploads\Models\Upload;

class StoreUploadAction
{
    use HasActionHelpers;

    public function handle(UploadedFile $file): Upload
    {
        $originalName = $file->getClientOriginalName();
        $fileType = $file->getClientMimeType();
        $fileSize = $file->getSize();

        $path = $file->store("uploads", "public");

        return Upload::create([
            "name" => $originalName,
            "path" => $path,
            "type" => $fileType,
            "size" => $fileSize,
        ]);
    }
}
