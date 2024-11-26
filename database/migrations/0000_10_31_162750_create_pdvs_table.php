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
        Schema::create('pdvs', function (Blueprint $table) {
            $table->id();
            $table->enum('unit', ['Distribuidora','Franquicia','DAM']);
            $table->string('spot',30)->unique();
            $table->string('address',255)->nullable();
            $table->boolean('status')->default(true);
            $table->foreignId('zonal_id')->constrained('zonals')->onDelete('restrict');
            $table->foreignId('horary_id')->constrained('horaries')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pdvs');
    }
};
