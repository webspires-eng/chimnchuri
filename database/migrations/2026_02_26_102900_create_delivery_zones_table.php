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
        Schema::create('delivery_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->decimal('min_distance', 5, 2)->nullable();
            $table->decimal('max_distance', 5, 2)->nullable();
            $table->decimal('delivery_fee', 8, 2)->nullable();
            $table->decimal('minimum_order_amount', 8, 2)->nullable();
            $table->boolean('is_active')->default(true)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_zones');
    }
};
