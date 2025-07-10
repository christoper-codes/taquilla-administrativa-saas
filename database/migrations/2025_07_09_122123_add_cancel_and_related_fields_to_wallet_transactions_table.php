<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up():void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('related_transaction_id')->nullable()->after('id');
            $table->foreign('related_transaction_id')->references('id')->on('wallet_transactions');
        });
    }

    public function down():void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropForeign(['related_transaction_id']);
            $table->dropColumn(['related_transaction_id']);
        });
    }
};
