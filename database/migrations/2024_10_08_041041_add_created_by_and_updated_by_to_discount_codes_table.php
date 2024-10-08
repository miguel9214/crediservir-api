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
        Schema::table('discount_codes', function (Blueprint $table) {
          // Añadir las columnas created_by_user y updated_by_user
          $table->unsignedBigInteger('created_by_user')->nullable()->after('updated_at');
          $table->unsignedBigInteger('updated_by_user')->nullable()->after('created_by_user');

          // Añadir las llaves foráneas
          $table->foreign('created_by_user')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
          $table->foreign('updated_by_user')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('discount_codes', function (Blueprint $table) {
             // Eliminar las llaves foráneas
             $table->dropForeign(['created_by_user']);
             $table->dropForeign(['updated_by_user']);

             // Eliminar las columnas
             $table->dropColumn('created_by_user');
             $table->dropColumn('updated_by_user');
        });
    }
};
