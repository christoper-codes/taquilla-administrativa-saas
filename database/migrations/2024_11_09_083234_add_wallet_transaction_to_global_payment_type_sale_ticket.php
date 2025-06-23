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
        Schema::table('global_payment_type_sale_ticket', function (Blueprint $table) {

            $table->unsignedBigInteger('wallet_transaction_id')->nullable()->after('global_card_payment_type_id');
            $table->foreign('wallet_transaction_id')->references('id')->on('wallet_transactions');

            $table->dropForeign(['sale_ticket_id']);
            $table->unsignedBigInteger('sale_ticket_id')->nullable()->change();
            $table->foreign('sale_ticket_id')->references('id')->on('sale_tickets');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('global_payment_type_sale_ticket', function (Blueprint $table) {
            $table->dropForeign(['wallet_transaction_id']);
            $table->dropColumn('wallet_transaction_id');
        });
    }
};
