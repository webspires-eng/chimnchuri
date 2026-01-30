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
        Schema::create('item_sizes', function (Blueprint $table) {
            $table->id();

            $table->uuid("uuid")->nullable()->unique();

            $table->foreignId("item_id")->constrained("items")->onDelete("cascade");

            $table->string("name")->default("regular");
            $table->decimal("price", 10, 2)->default(0);

            $table->decimal("discount", 10, 2)->nullable()->default(0);
            $table->enum("discount_type", ["fixed", "percentage"])->default("percentage")->nullable();

            $table->string('size_image')->nullable();
            $table->string('serves')->default(1)->nullable();

            $table->boolean('is_active')->default(true);

            $table->softDeletes();
            $table->timestamps();

            $table->unique(['item_id', 'name']);
            $table->index(['item_id', 'is_active']);
            $table->index('price');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_sizes');
    }
};
