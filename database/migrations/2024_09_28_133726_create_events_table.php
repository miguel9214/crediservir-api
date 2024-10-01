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
            $table->string('title');
            $table->text('description');
            $table->dateTime('date');
            $table->string('location'); 
            $table->integer('capacity');
            $table->enum('type', ['free', 'paid']);
            $table->decimal('base_price', 8, 2)->nullable(); 
            $table->foreignId('category_id')->constrained('categories');
            $table->boolean('status')->default(1);
            $table->date('registration_open');
            $table->date('registration_close');
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
