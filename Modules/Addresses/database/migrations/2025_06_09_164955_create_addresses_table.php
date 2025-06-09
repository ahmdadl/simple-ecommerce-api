<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->uid();
            $table->foreignUlid('user_id')->constrained()->noActionOnDelete();
            $table->foreignUlid('city_id')->constrained()->noActionOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('title');
            $table->string('address');
            $table->string('phone');
            $table->string('email');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
