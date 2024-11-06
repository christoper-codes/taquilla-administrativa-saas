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
        Schema::table('events', function (Blueprint $table) {
                $table->unsignedBigInteger('global_season_id')->nullable()->after('id');
                $table->foreign('global_season_id')->references('id')->on('global_seasons');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['global_season_id']);
            $table->dropColumn('global_season_id');
        });
    }
};
