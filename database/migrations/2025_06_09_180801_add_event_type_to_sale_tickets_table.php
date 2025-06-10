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
        Schema::table('sale_tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('event_id')->nullable()->after('stadium_id');
            $table->string('purchase_type')->nullable()->after('promotion_quantity');
        });

        Schema::table('sale_tickets', function (Blueprint $table) {
            $table->foreign('event_id')->references('id')->on('events');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_tickets', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropColumn('event_id');
            $table->dropColumn('event_type');
        });
    }
};
