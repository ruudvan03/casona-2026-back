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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // Ej: "Habitación Doble"
            $table->string('slug')->unique(); // Para la URL en Astro
            $table->text('description')->nullable();
            $table->decimal('price_per_night', 10, 2);
            $table->integer('capacity');      // Ej: 2 personas
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
