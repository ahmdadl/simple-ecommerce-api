<?php

use Illuminate\Support\Facades\Storage;
use Modules\Uploads\Actions\ShowUploadAction;
use Modules\Uploads\Models\Upload;

beforeEach(function () {
    // Fake the 'public' storage disk to avoid real file operations
    Storage::fake("public");
});

it("retrieves file content and MIME type for an existing upload", function () {
    // Arrange: Create a fake file and Upload model
    $fileContent = "This is a test file content";
    $filePath = "uploads/testfile.txt";
    Storage::disk("public")->put($filePath, $fileContent); // Simulate file in storage
    $upload = Upload::factory()->create([
        "path" => $filePath,
        "type" => "text/plain",
    ]);

    // Act: Execute the action
    $action = new ShowUploadAction();
    $result = $action->handle($upload);

    // Assert: Check the returned array
    expect($result)
        ->toBeArray()
        ->toHaveKeys(["content", "mime_type"])
        ->content->toBe($fileContent)
        ->mime_type->toBe("text/plain");
});

it("throws FileNotFoundException when file does not exist", function () {
    // Arrange: Create an Upload model with a non-existent file path
    $upload = Upload::factory()->create([
        "path" => "uploads/nonexistent.txt",
        "type" => "text/plain",
    ]);

    // Act & Assert: Expect the FileNotFoundException to be thrown
    expect(fn() => (new ShowUploadAction())->handle($upload))->toThrow(
        \Illuminate\Contracts\Filesystem\FileNotFoundException::class,
        __("core::t.file_not_found")
    );
});

it("resolves MIME type from storage when not set in model", function () {
    // Arrange: Create a fake file with no MIME type in the model
    $fileContent = "Sample image content";
    $filePath = "uploads/testimage.jpg";
    Storage::disk("public")->put($filePath, $fileContent); // Simulate file
    $upload = Upload::factory()->create([
        "path" => $filePath,
        "type" => null, // No MIME type in model
    ]);

    // Act: Execute the action
    $action = new ShowUploadAction();
    $result = $action->handle($upload);

    // Assert: Check the result uses the resolved MIME type
    expect($result)->content->toBe($fileContent)->mime_type->toBe("image/jpeg");
});
