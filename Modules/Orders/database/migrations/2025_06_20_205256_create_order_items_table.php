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
        Schema::create("order_items", function (Blueprint $table) {
            $table->uid();
            $table->foreignUlid("order_id")->constrained()->cascadeOnDelete();
            $table
                ->foreignUlid("product_id")
                ->constrained()
                ->noActionOnDelete();
            $table->integer("quantity");
            $table->json("totals");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("order_items");
    }
};
