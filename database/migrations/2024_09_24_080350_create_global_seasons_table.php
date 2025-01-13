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
        Schema::create('global_seasons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stadium_id')->constrained('stadiums');
            $table->foreignId('global_image_id')->nullable()->constrained('global_images');
            $table->foreignId('global_season_type_id')->constrained('global_season_types');
            $table->foreignId('league_type_id')->constrained('league_types');
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('description')->nullable();
            $table->boolean('enabled_for_season_tickets')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('global_seasons');
    }
};
