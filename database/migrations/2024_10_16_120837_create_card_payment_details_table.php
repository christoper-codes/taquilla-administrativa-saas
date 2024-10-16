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
        Schema::create('card_payment_details', function (Blueprint $table) {
            $table->id();
            $table->integer('id_sale_ticket');
            $table->string('full_name_user');
            $table->string('email_address');
            $table->string('id_billing');
            $table->string('status');
            $table->string('amount');
            $table->string('card_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_payment_details');
    }
};
