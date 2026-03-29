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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('url');            // Ruta de la imagen
            $table->string('alt_text')->nullable();
            
            // Categoría para filtrar en el frontend
            // 'food_gallery', 'hotel_gallery', 'venue_gallery', 'room_main'
            $table->string('category')->index(); 
            
            // Opcional: Si quieres vincular una foto a un cuarto específico
            $table->nullableMorphs('imageable'); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
