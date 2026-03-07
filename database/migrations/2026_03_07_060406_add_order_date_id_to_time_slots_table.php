<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('time_slots', function (Blueprint $table) {
            $table->foreignId('order_date_id')->nullable()->after('id')->constrained('order_dates')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('time_slots', function (Blueprint $table) {
            $table->dropForeign(['order_date_id']);
            $table->dropColumn('order_date_id');
        });
    }
};
