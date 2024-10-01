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
        Schema::create('leading_companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('global_image_id')->nullable()->constrained('global_images');
            $table->foreignId('global_address_id')->constrained('global_addresses');
            $table->string('name');
            $table->string('email');
            $table->string('phone_number');
            $table->string('description');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leading_companies');
    }
};
