<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_dates', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->string('day_name'); // friday, saturday
            $table->enum('status', ['open', 'closed', 'sold_out'])->default('closed');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_dates');
    }
};
