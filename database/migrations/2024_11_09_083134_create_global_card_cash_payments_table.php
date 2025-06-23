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
        Schema::create('global_card_cash_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('global_payment_type_sale_ticket_id')->constrained('global_payment_type_sale_ticket','id','card_payment_type_sale_ticket_id_fk')->nullable();
            $table->decimal('amount_received', 14, 4)->default(0.0000);
            $table->decimal('amount_change_given', 14, 4)->default(0.0000);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('global_card_cash_payments');
    }
};
