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
        Schema::create('seat_catalogues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stadium_id')->constrained('stadiums');
            $table->foreignId('zone_type_id')->nullable()->constrained('zone_types');
            $table->foreignId('seat_type_id')->constrained('seat_types');
            $table->foreignId('row_type_id')->nullable()->constrained('row_types');
            $table->string('zone')->nullable();
            $table->string('row')->nullable();
            $table->string('seat')->nullable();
            $table->string('code')->unique();
            $table->string('description')->default('generic');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seat_catalogues');
    }
};
