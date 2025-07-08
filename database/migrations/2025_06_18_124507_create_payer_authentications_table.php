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
        Schema::create('payer_authentications', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('cardType')->nullable();
            $table->json('enroll_payload')->nullable();
            $table->json('enroll_response')->nullable();
            $table->json('challenge_payload')->nullable();
            $table->json('challenge_response')->nullable();
            $table->json('payment_payload')->nullable();
            $table->json('payment_response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payer_authentications');
    }
};
