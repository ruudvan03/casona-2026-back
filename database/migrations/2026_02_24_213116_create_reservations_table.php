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
    Schema::create('reservations', function (Blueprint $table) {
        $table->id();
        $table->string('folio')->unique()->nullable();
        $table->foreignId('room_id')->constrained()->onDelete('cascade');
        $table->string('customer_name');
        $table->string('customer_email');
        $table->string('customer_phone'); 
        $table->date('check_in');
        $table->date('check_out');
        $table->decimal('total_price', 10, 2);
        $table->string('payment_method')->default('transfer'); 
        $table->string('status')->default('pending');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};