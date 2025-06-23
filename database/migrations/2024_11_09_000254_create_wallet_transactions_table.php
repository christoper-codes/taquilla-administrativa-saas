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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_account_id')->constrained('wallet_accounts')->onDelete('cascade');
            $table->foreignId('wallet_transaction_status_id')->constrained('wallet_transaction_statuses');
            $table->foreignId('wallet_transaction_type_id')->constrained('wallet_transaction_types');
            $table->foreignId('wallet_recharge_amount_id')->nullable()->constrained('wallet_recharge_amounts');
            $table->integer('pos_ticket_id')->nullable();
            $table->integer('pos_cash_register_id')->nullable();
            $table->string('description')->nullable();
            $table->decimal('movement_amount', 14, 4)->default(0.0000);
            $table->decimal('cash_back_generated', 14, 4)->default(0.0000);
            $table->boolean('paid_with_cashless')->default(false);
            $table->boolean('paid_with_cashback')->default(false);
            $table->decimal('cashback_balance_account_before_transaction', 14, 4)->default(0.0000);
            $table->decimal('cashback_balance_account_after_transaction', 14, 4)->default(0.0000);
            $table->decimal('cashless_balance_account_before_transaction', 14, 4)->default(0.0000);
            $table->decimal('cashless_balance_account_after_transaction', 14, 4)->default(0.0000);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
