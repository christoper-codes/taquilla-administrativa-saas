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
        Schema::create('wallet_exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_wallet_currency_id')->constrained('wallet_currencies');
            $table->foreignId('to_wallet_currency_id')->constrained('wallet_currencies');
            $table->decimal('rate', 14, 4);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_exchange_rates');
    }
};
