<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("order_item_products", function (Blueprint $table) {
            $table->uid();
            $table
                ->foreignUlid("order_item_id")
                ->constrained()
                ->cascadeOnDelete();
            $table
                ->foreignUlid("product_id")
                ->constrained()
                ->noActionOnDelete();
            $table
                ->foreignUlid("category_id")
                ->constrained()
                ->noActionOnDelete();
            $table->foreignUlid("brand_id")->constrained()->noActionOnDelete();

            $table->json("title");
            $table->json("category_title");
            $table->json("brand_title");

            $table->json("images")->nullable();
            $table->decimal("price", 10, 2);
            $table->decimal("sale_price", 10, 2)->nullable();

            $table->string("sku")->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("order_item_products");
    }
};
