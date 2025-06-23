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
        Schema::table('event_seat_catalog', function (Blueprint $table) {
            $table->decimal('original_price', 14, 4)->nullable()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_seat_catalog', function (Blueprint $table) {
            $table->dropColumn('original_price');
        });
    }
};
