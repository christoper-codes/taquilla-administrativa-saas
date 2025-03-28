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
        Schema::create('installment_payment_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_ticket_id')->constrained('sale_tickets');
            $table->decimal('amount_received', 14, 4)->default('0.0000');
            $table->decimal('total_amount', 14, 4)->default('0.0000');
            $table->decimal('total_returned', 14, 4)->default('0.0000');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installment_payment_histories');
    }
};
