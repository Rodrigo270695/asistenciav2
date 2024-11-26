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
        Schema::create('detailhoraries', function (Blueprint $table) {
            $table->id();
            $table->enum('week', ['Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo']);
            $table->time('hi');
            $table->time('rs')->nullable();
            $table->time('ri')->nullable();
            $table->time('hs');
            $table->foreignId('horary_id')->constrained('horaries')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detailhoraries');
    }
};
