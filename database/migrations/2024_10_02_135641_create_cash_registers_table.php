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
        Schema::create('cash_registers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_office_id')->constrained('ticket_offices');
            $table->foreignId('cash_register_type_id')->constrained('cash_register_types');
            $table->foreignId('seller_user_opening_id')->constrained('users');
            $table->foreignId('seller_user_closing_id')->nullable()->constrained('users');
            $table->string('description')->nullable();
            $table->boolean('is_open')->default(true);
            $table->boolean('confirmed_closure')->nullable()->default(false);
            $table->integer('batch_cash_register')->nullable();
            $table->string('batch_code')->nullable();
            $table->decimal('opening_balance', 14, 4)->default('0.0000');
            $table->decimal('current_balance', 14, 4)->default('0.0000');
            $table->decimal('closing_balance', 14, 4)->nullable();
            $table->dateTime('opening_time')->nullable();
            $table->dateTime('closing_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_registers');
    }
};
