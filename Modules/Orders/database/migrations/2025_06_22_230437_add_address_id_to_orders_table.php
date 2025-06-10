<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Orders\Models\OrderAddress;
use Modules\Orders\Models\OrderCoupon;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table("orders", function (Blueprint $table) {
            $table
                ->foreignUlid("shipping_address_id")
                ->constrained("order_addresses");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("orders", function (Blueprint $table) {
            $table->dropForeignIdFor(OrderCoupon::class);
            $table->dropForeignIdFor(OrderAddress::class);
        });
    }
};
