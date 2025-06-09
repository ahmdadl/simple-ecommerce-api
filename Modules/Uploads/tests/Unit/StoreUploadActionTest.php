<?php

use Illuminate\Http\UploadedFile;
use Modules\Uploads\Actions\StoreUploadAction;
use Modules\Uploads\Models\Upload;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    // Fake the storage disk to avoid real file operations
    Storage::fake("public");
});

it("stores an uploaded file and creates an upload record", function () {
    // Arrange: Create a fake uploaded file
    $file = UploadedFile::fake()->create(
        "testfile.txt", // Original name
        1024, // Size in bytes
        "text/plain" // MIME type
    );

    // Act: Execute the action
    $action = new StoreUploadAction();
    $upload = $action->handle($file);

    // Assert: Check the Upload model was created with correct attributes
    expect($upload)
        ->toBeInstanceOf(Upload::class)
        ->name->toBe("testfile.txt")
        ->type->toBe("text/plain")
        ->size->toBe(1024 * 1024)
        ->path->toMatch('/^uploads\/[a-zA-Z0-9]+\.txt$/'); // Path will be 'uploads/{hash}.txt'

    // Assert: Verify the file was stored in the 'public' disk
    Storage::disk("public")->assertExists($upload->path);
});

it("stores an image file correctly", function () {
    // Arrange: Create a fake image file
    $file = UploadedFile::fake()->image(
        name: "testimage.jpg", // Original name
        width: 100, // Width in pixels
        height: 100 // Height in pixels
    );

    // Act: Execute the action
    $action = new StoreUploadAction();
    $upload = $action->handle($file);

    // Assert: Check the Upload model attributes
    expect($upload)
        ->toBeInstanceOf(Upload::class)
        ->name->toBe("testimage.jpg")
        ->type->toBe("image/jpeg")
        ->size->toBeGreaterThan(0) // Size will vary based on fake image
        ->path->toMatch('/^uploads\/[a-zA-Z0-9]+\.jpg$/');

    // Assert: Verify the file exists in storage
    Storage::disk("public")->assertExists($upload->path);
});

it("handles files with special characters in the name", function () {
    // Arrange: Create a fake file with special characters in the name
    $file = UploadedFile::fake()->create(
        "test-file_with.special@chars.txt",
        2048,
        "text/plain"
    );

    // Act: Execute the action
    $action = new StoreUploadAction();
    $upload = $action->handle($file);

    // Assert: Check the Upload model attributes
    expect($upload)
        ->toBeInstanceOf(Upload::class)
        ->name->toBe("test-file_with.special@chars.txt")
        ->type->toBe("text/plain")
        ->size->toBe(2048 * 1024)
        ->path->toMatch('/^uploads\/[a-zA-Z0-9]+\.txt$/'); // Hash name won't include special chars

    // Assert: Verify the file exists in storage
    Storage::disk("public")->assertExists($upload->path);
});
