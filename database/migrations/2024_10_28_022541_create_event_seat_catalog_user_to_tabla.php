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
        Schema::create('event_seat_catalog_user', function (Blueprint $table) {
            $table->id();
            $table->integer('event_seat_catalog_id');
            $table->integer('sender_user_id');
            $table->integer('receiver_user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_seat_catalog_user');
    }
};
