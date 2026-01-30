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
        Schema::create('addon_categories', function (Blueprint $table) {
            $table->id();

            $table->uuid("uuid")->nullable()->unique();
            $table->string("name")->unique();
            $table->string("description")->nullable();
            $table->string("image")->nullable();

            $table->boolean("is_active")->default(true);

            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addon_categories');
    }
};
