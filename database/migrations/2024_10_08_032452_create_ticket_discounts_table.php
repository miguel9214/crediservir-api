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
        Schema::create('ticket_discounts', function (Blueprint $table) {
            $table->id();  // Columna `id` como PRIMARY KEY con AUTO_INCREMENT
            $table->unsignedBigInteger('ticket_id')->nullable();  // Columna `ticket_id`
            $table->unsignedBigInteger('discount_id')->nullable();  // Columna `discount_id`
            $table->double('amount')->nullable();  // Columna `amount`
            $table->decimal('percentage', 5, 2)->nullable();  // Columna `percentage` (5 dígitos, 2 decimales)
            $table->timestamps();  // Crea columnas `created_at` y `updated_at`

            // Índices
            $table->index('ticket_id');
            $table->index('discount_id');

            // Foreign keys (llaves foráneas)
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('discount_id')->references('id')->on('discount_codes')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_discounts');
    }
};
