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
        Schema::create("order_addresses", function (Blueprint $table) {
            $table->uid();
            $table->foreignUlid("address_id")->constrained();
            $table->foreignUlid("user_id")->constrained();
            $table->foreignUlid("city_id")->constrained();
            $table->string("city_title")->nullable();
            $table->float("shipping_fees")->nullable();
            $table->string("name", 50);
            $table->string("title", 50)->nullable();
            $table->string("address", 250);
            $table->string("phone", length: 15);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("order_addresses");
    }
};
