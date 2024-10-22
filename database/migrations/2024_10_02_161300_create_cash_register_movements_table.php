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
        Schema::create('cash_register_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_register_id')->constrained('cash_registers');
            $table->foreignId('cash_register_movement_type_id')->constrained('cash_register_movement_types');
            $table->foreignId('sale_ticket_id')->nullable()->constrained('sale_tickets');
            $table->foreignId('sale_ticket_cancelation_id')->nullable()->constrained('sale_ticket_cancelations');
            $table->decimal('previous_balance', 14, 4)->default('0.0000');
            $table->decimal('movement_amount', 14, 4)->default('0.0000');
            $table->decimal('new_balance', 14, 4)->default('0.0000');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_register_movements');
    }
};
