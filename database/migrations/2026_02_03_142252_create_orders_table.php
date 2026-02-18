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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('order_number')->unique();

            $table->decimal('sub_total', 10, 2)->nullable();
            $table->decimal('discount_total', 10, 2)->nullable();
            $table->decimal('delivery_charges', 10, 2)->nullable();
            $table->decimal('tax_total', 10, 2)->nullable();
            $table->decimal('grand_total', 10, 2)->nullable();

            $table->string('payment_intent_id')->nullable();
            $table->enum('payment_method', ['cod', 'online']);
            $table->enum('payment_status', ['unpaid', "pending", 'paid', "failed", "refunded", 'cancelled'])->default('pending');

            $table->enum('order_status', [
                'pending',
                'accepted',
                'confirmed',
                'processing',
                "dispatched",
                'completed',
                'cancelled'
            ])->default('pending');

            $table->integer("steak_qty")->nullable()->default(0);
            $table->string("time_slot_id")->nullable();
            $table->string("time_slot")->nullable();

            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();

            $table->text('delivery_address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
