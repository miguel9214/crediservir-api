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
        Schema::create('discount_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Código de descuento
            $table->decimal('discount_percentage', 5, 2); // Porcentaje de descuento
            $table->date('valid_from'); // Fecha de inicio de validez
            $table->date('valid_until'); // Fecha de fin de validez
            $table->boolean('status')->default(1); // Estado del código (activo/inactivo)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_codes');
    }
};
