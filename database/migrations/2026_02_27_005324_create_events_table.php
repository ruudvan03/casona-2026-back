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
        Schema::create('events', function (Blueprint $table) {
        $table->id();
        $table->string('folio')->unique();
        $table->string('customer_name');
        $table->string('customer_phone')->nullable();
        $table->string('event_type'); 
        $table->date('event_date');
        $table->time('start_time'); // Hora de inicio
        $table->time('end_time');   // Hora de fin
        $table->decimal('total_price', 10, 2);
        $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
