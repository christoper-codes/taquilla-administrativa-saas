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
        Schema::create('sale_ticket_cancelations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_user_id')->constrained('users');
            $table->foreignId('sale_ticket_id')->constrained('sale_tickets');
            $table->decimal('total_amount', 14, 4)->default('0.0000');
            $table->string('reason');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_ticket_cancelations');
    }
};
