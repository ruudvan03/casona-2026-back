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
        Schema::table('rooms', function (Blueprint $table) {
            // Agregamos la columna image para guardar la ruta del archivo
            // La ponemos después de 'description' y que acepte nulos por si acaso
            $table->string('image')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            // Al hacer rollback, eliminamos la columna
            $table->dropColumn('image');
        });
    }
};