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
        Schema::create('price_type_seat_catalogue', function (Blueprint $table) {
            $table->id();
            $table->foreignId('price_type_id')->constrained('price_types');
            $table->foreignId('seat_catalogue_id')->constrained('seat_catalogues');
            $table->foreignId('price_catalogue_id')->constrained('price_catalogues');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_type_seat_catalogue');
    }
};
