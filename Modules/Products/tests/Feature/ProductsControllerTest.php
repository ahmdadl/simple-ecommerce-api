<?php

use Modules\Categories\Models\Category;
use Modules\Products\Models\Product;

use function Pest\Laravel\getJson;

it("gets_all_active_products", function () {
    $activeProducts = Product::factory(7)->create([
        "is_active" => true,
    ]);
    Product::factory(2)->create([
        "is_active" => false,
    ]);

    expect(Product::count())->toBe(9);

    getJson(route("api.products.index"))
        ->assertOk()
        ->assertJsonCount(7, "data.records");
});

it("gets_active_product", function () {
    $product = Product::factory()->create([
        "is_active" => true,
    ]);

    getJson(route("api.products.show", $product->slug))
        ->assertOk()
        ->assertJsonPath("data.record.id", $product->id);

    getJson(route("api.products.show", $product->slug))
        ->assertOk()
        ->assertJsonPath("data.record.category.id", $product->category_id);
});