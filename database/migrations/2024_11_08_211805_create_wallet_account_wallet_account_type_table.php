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
        Schema::create('wallet_account_wallet_account_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_account_id')->constrained('wallet_accounts')->onDelete('cascade');
            $table->foreignId('wallet_account_type_id')->constrained('wallet_account_types', 'id', 'wallet_account_type_id_fk')->onDelete('cascade');
            $table->decimal('current_balance', 14, 4)->default(0);
            $table->boolean('is_active')->default(true);
            $table->date('expiration_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_account_wallet_account_type');
    }
};
