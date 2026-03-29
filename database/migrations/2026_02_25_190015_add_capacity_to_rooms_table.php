<?php

use Illuminate\Support\Facades\Schema; // Asegúrate de importar esto arriba
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rooms', function (Blueprint $table) {
            // 1. Verificamos si 'capacity' NO existe antes de agregarla
            if (!Schema::hasColumn('rooms', 'capacity')) {
                $table->integer('capacity')->default(2)->after('price_per_night');
            }

            // 2. Verificamos si 'capacity_label' NO existe antes de agregarla
            if (!Schema::hasColumn('rooms', 'capacity_label')) {
                $table->string('capacity_label')->nullable()->after('capacity');
            }
        });
    }

    public function down()
    {
        Schema::table('rooms', function (Blueprint $table) {
            if (Schema::hasColumn('rooms', 'capacity')) {
                $table->dropColumn('capacity');
            }
            if (Schema::hasColumn('rooms', 'capacity_label')) {
                $table->dropColumn('capacity_label');
            }
        });
    }
};