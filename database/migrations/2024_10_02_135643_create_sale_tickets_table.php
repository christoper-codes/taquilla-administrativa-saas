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
        Schema::create('sale_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events');
            $table->foreignId('seller_user_id')->constrained('users');
            $table->foreignId('cash_register_id')->constrained('cash_registers');
            $table->foreignId('sale_ticket_status_id')->constrained('sale_ticket_statuses');
            $table->foreignId('price_type_id')->constrained('price_types');
            $table->decimal('amount_received', 14, 4)->default('0.0000');
            $table->decimal('total_amount', 14, 4)->default('0.0000');
            $table->decimal('total_returned', 14, 4)->default('0.0000');
            $table->boolean('is_online')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_tickets');
    }
};
