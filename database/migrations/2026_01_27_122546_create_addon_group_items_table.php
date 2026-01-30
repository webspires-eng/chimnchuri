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
        Schema::create('addon_group_items', function (Blueprint $table) {
            $table->id();

            $table->uuid("uuid")->nullable()->unique();

            $table->foreignId("addon_group_id")->constrained("addon_groups")->cascadeOnDelete();
            $table->foreignId("addon_item_id")->constrained("addon_items")->cascadeOnDelete();

            $table->decimal("price", 10, 2)->nullable();

            $table->boolean("is_active")->default(true);

            $table->integer("sort_order")->default(0);

            $table->softDeletes();
            $table->timestamps();

            $table->unique(["addon_group_id", "addon_item_id"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addon_group_items');
    }
};
