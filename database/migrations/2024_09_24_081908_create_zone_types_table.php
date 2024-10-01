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
        Schema::create('zone_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stadium_id')->constrained('stadiums');
            $table->foreignId('global_image_id')->nullable()->constrained('global_images');
            $table->string('name');
            $table->string('code')->unique();
            $table->integer('capacity');
            $table->integer('rows');
            $table->integer('columns');
            $table->integer('seats');
            $table->integer('vip_seats');
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zone_types');
    }
};
