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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events'); // Relación con la tabla de eventos
            $table->foreignId('attendee_id')->constrained('attendees'); // Relación con la tabla de asistentes
            $table->enum('ticket_type', ['free', 'general', 'vip']); // Tipo de entrada
            $table->decimal('price', 8, 2); // Precio total de la entrada
            $table->string('discount_code')->nullable(); // Código promocional aplicado
            $table->dateTime('purchase_date'); // Fecha y hora de la compra
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
