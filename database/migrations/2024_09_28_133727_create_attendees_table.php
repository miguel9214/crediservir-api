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
        Schema::create('attendees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name'); // Primer nombre del asistente
            $table->string('last_name'); // Apellido del asistente
            $table->date('birth_date'); // Fecha de nacimiento
            $table->string('email')->unique(); // Email del asistente
            $table->string('phone'); // Número de celular
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendees');
    }
};
