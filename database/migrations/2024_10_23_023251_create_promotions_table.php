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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_type_id')->constrained('promotion_types');
            $table->foreignId('stadium_id')->constrained('stadiums');
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('generic_seats_allowed')->nullable();
            $table->integer('promotional_seats_allowed')->nullable();
            $table->integer('maximun_promotions_allowed')->nullable();
            $table->integer('percent_allow')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_active_online')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
