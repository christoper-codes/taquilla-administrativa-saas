<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePlatformSettingsTable extends Migration
{
        public function up()
        {
            Schema::table('platform_settings', function (Blueprint $table) {
                // Eliminar columnas existentes
                $table->dropColumn('key');
                $table->dropColumn('value');
                $table->dropColumn('platform');

                // Agregar columna JSON
                $table->json('settings');
            });
        }

        public function down()
        {
            Schema::table('platform_settings', function (Blueprint $table) {

                $table->dropColumn('settings');

                // Restaurar columnas existentes
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->enum('platform', ['web', 'app'])->nullable();
            });
        }
}
