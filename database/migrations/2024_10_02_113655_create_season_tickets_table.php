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
        Schema::create('season_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('seat_catalogue_id')->constrained('seat_catalogues');
            $table->foreignId('global_season_id')->nullable()->constrained('global_seasons');
            $table->foreignId('serie_id')->nullable()->constrained('series');
            $table->string('holder_name');
            $table->string('holder_surname');
            $table->string('holder_email');
            $table->string('holder_phone');
            $table->string('holder_zip_code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('season_tickets');
    }
};
