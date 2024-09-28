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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Título del evento
            $table->text('description'); // Descripción del evento
            $table->dateTime('date'); // Fecha y hora del evento
            $table->string('location'); // Ubicación del evento
            $table->integer('capacity'); // Capacidad máxima
            $table->enum('type', ['free', 'paid']); // Tipo de evento (gratis o pago)
            $table->decimal('base_price', 8, 2)->nullable(); // Precio base del evento
            $table->foreignId('category_id')->constrained('categories'); // Relación con categorías
            $table->boolean('status')->default(1); // Estado del evento (abierto/cerrado)
            $table->date('registration_open'); // Fecha de apertura de inscripciones
            $table->date('registration_close'); // Fecha de cierre de inscripciones
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
