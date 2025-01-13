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
        Schema::create('event_seat_catalog', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events');
            $table->foreignId('seat_catalogue_id')->constrained('seat_catalogues');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('season_ticket_id')->nullable()->constrained('season_tickets');
            $table->foreignId('seat_catalogue_status_id')->constrained('seat_catalogue_statuses');
            $table->foreignId('sale_ticket_id')->nullable()->constrained('sale_tickets');
            $table->string('qr')->nullable();
            $table->decimal('price', 14, 4)->nullable();
            $table->string('purchase_type')->nullable();
            $table->boolean('is_gift')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_seat_catalog');
    }
};
