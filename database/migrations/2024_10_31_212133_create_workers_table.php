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
        Schema::create('workers', function (Blueprint $table) {
            $table->id();
            $table->string('name',30);
            $table->string('lastname',30);
            $table->enum('document_type',['DNI','CARNET DE EXTRANJERIA','OTROS']);
            $table->string('num_document',15)->unique();
            $table->date('entry_date');
            $table->date('exit_date')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('phone', 11)->nullable();
            $table->string('email',0)->nullable();
            $table->string('address',255)->nullable()->unique();
            $table->foreignId('charge_id')->constrained('charges')->restrictOnDelete();
            $table->foreignId('pdv_id')->constrained('pdvs')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workers');
    }
};
