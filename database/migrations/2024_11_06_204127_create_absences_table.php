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
        Schema::create('absences', function (Blueprint $table) {
            $table->id();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('file', 3072)->nullable();
            $table->enum('status', ['Por aprobar','Aprobado','Rechazado','Observado'])->default('Por aprobar');
            $table->text('status_detail')->nullable();
            $table->foreignId('worker_id')->constrained('workers')->restrictOnDelete();
            $table->foreignId('reason_id')->constrained('reasons')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absences');
    }
};
