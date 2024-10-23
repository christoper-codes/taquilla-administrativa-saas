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
        Schema::create('event_seat_catalog_promotion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_seat_catalog_id')->constrained('event_seat_catalog');
            $table->foreignId('promotion_id')->constrained('promotions');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_seat_catalog_promotion');
    }
};
