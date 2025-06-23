<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountRoleWalletAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_role_wallet_account', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_account_role_id')->constrained('wallet_account_roles')->onDelete('cascade');
            $table->foreignId('wallet_account_id')->constrained('wallet_accounts')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_role_wallet_account');
    }
}
