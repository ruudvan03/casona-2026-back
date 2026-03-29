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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            // Datos del cliente
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            
            // Relación Polimórfica (Permite reservar Room o Venue)
            $table->unsignedBigInteger('bookable_id');
            $table->string('bookable_type'); // App\Models\Room o App\Models\Venue

            // Fechas
            $table->dateTime('start_time'); // Check-in o Inicio evento
            $table->dateTime('end_time');   // Check-out o Fin evento
            
            // Estado y Costos
            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            
            // Contratos (Ruta del PDF generado)
            $table->string('contract_path')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
