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
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            $table->uuid('uuid')->unique()->nullable();

            $table->string('name');
            $table->string('slug')->unique();

            $table->text("short_description")->nullable();
            $table->text("description")->nullable();
            $table->string("label")->nullable();

            $table->boolean("is_taxable")->default(true);
            $table->boolean("is_discountable")->default(true);
            $table->boolean("is_active")->default(true);

            $table->boolean("is_featured")->default(false);
            $table->boolean("is_popular")->default(false);


            $table->integer('sort_order')->default(0);

            $table->softDeletes();


            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
