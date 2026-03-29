<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('rooms', function (Blueprint $table) {
            // Agregamos la columna para la foto de portada
            if (!Schema::hasColumn('rooms', 'image_path')) {
                $table->string('image_path')->nullable()->after('is_available');
            }
        });
    }

    public function down()
    {
        Schema::table('rooms', function (Blueprint $table) {
            if (Schema::hasColumn('rooms', 'image_path')) {
                $table->dropColumn('image_path');
            }
        });
}
};
