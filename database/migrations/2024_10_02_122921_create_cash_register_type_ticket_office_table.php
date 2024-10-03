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
        Schema::create('cash_register_type_ticket_office', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_register_type_id')->constrained('cash_register_types');
            $table->foreignId('ticket_office_id')->constrained('ticket_offices');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_register_type_ticket_office');
    }
};
