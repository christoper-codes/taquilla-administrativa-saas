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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stadium_id')->default(1);
            $table->foreign('stadium_id')->references('id')->on('stadiums');
            $table->foreignId('event_type_id')->constrained('event_types');
            $table->foreignId('serie_id')->constrained('series');
            $table->foreignId('global_image_id')->nullable()->constrained('global_images');
            $table->foreignId('event_visibility_type_id')->constrained('event_visibility_types');
            $table->string('name');
            $table->string('slug');
            $table->string('description');
            $table->string('promotion_announcement')->default('Proximamente nuevas promociones!!');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
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
        Schema::dropIfExists('events');
    }
};
