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
        Schema::create('global_payment_type_sale_ticket', function (Blueprint $table) {
            $table->id();
            $table->foreignId('global_payment_type_id')->constrained('global_payment_types');
            $table->foreignId('sale_ticket_id')->nullable()->constrained('sale_tickets');
            $table->foreignId('courtesy_type_id')->nullable()->constrained('courtesy_types');
            $table->foreignId('reason_agreement_id')->nullable()->constrained('reason_agreements');

            $table->unsignedBigInteger('global_card_payment_type_id')->nullable();
            $table->foreign('global_card_payment_type_id', 'global_card_payment_type_id_foreign')->references('id')->on('global_card_payment_types');

            $table->unsignedBigInteger('installment_payment_history_id')->nullable();
            $table->foreign('installment_payment_history_id', 'installment_payment_history_id_foreign')->references('id')->on('installment_payment_histories');

            $table->decimal('amount', 14, 4)->default('0.0000');
            $table->string('reason_courtesy')->nullable();
            $table->decimal('original_amount', 14, 4)->default('0.0000');
            $table->date('payment_date')->nullable()->useCurrent();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('global_payment_type_sale_ticket', function (Blueprint $table) {
            $table->dropForeign('fk_card_payment_type');
        });

        Schema::dropIfExists('global_payment_type_sale_ticket');
    }
};
