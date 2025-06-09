<?php

namespace Modules\Uploads\Actions;

use Illuminate\Support\Facades\Storage;
use Modules\Uploads\Models\Upload;
use Throwable;

class ShowUploadAction
{
    /**
     * Execute the action to retrieve the uploaded file content.
     */
    public function handle(Upload $upload): array|Throwable
    {
        // Get the file path from the upload model
        $filePath = $upload->path;

        // Check if the file exists in storage
        if (!Storage::disk("public")->exists($filePath)) {
            throw new \Illuminate\Contracts\Filesystem\FileNotFoundException(
                __("core::t.file_not_found")
            );
        }

        // Retrieve the file content
        $fileContent = Storage::disk("public")->get($filePath);

        // Determine the MIME type
        $mimeType = $this->resolveMimeType($upload, $filePath);

        // Return the file content and MIME type as an array
        return [
            "content" => $fileContent,
            "mime_type" => $mimeType,
        ];
    }

    /**
     * Resolve the MIME type from the upload model or storage.
     */
    private function resolveMimeType(
        Upload $upload,
        string $filePath
    ): string|Throwable {
        $mimeType =
            $upload->type ?? Storage::disk("public")->mimeType($filePath);

        if (!$mimeType) {
            throw new \Exception("Unable to determine MIME type for the file.");
        }

        return $mimeType;
    }
}
