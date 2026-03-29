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
            // Agregamos la columna faltante
            if (!Schema::hasColumn('rooms', 'is_available')) {
                $table->boolean('is_available')->default(true)->after('description');
            }
        });
    }

    public function down()
    {
        Schema::table('rooms', function (Blueprint $table) {
            if (Schema::hasColumn('rooms', 'is_available')) {
                $table->dropColumn('is_available');
            }
        });
    }
};
