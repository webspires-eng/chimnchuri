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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            // Basic Info
            $table->string('name');
            $table->string('slug')->nullable()->unique();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            // Location
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();


            // CURRENCY 
            $table->string('currency_code')->nullable()->default('PKR');
            $table->string('currency_symbol')->nullable()->default('Rs.');

            // Ordering Settings
            $table->boolean('is_online_enabled')->default(true);
            $table->boolean('is_cod_enabled')->default(true);
            $table->boolean('is_delivery_enabled')->default(true);
            $table->boolean('is_pickup_enabled')->default(true);
            $table->boolean('is_order_enabled')->default(true);

            // Delivery Settings
            $table->decimal('delivery_fee', 10, 2)->nullable()->default(0);
            $table->decimal('min_order_amount', 10, 2)->nullable()->default(0);
            $table->integer('estimated_delivery_time')->nullable(); // in minutes
            $table->integer('estimated_pickup_time')->nullable(); // in minutes

            // Tax Settings
            $table->decimal('tax_percentage', 10, 2)->nullable()->default(0);

            // Status
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
