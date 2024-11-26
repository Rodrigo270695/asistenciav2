<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assists', function (Blueprint $table) {
            $table->id();
            $table->date('current_date')->nullable();
            $table->time('hi')->nullable();
            $table->unsignedTinyInteger('status_entry')->nullable();
            $table->time('rs')->nullable();
            $table->unsignedTinyInteger('status_foodout')->nullable();
            $table->time('ri')->nullable();
            $table->unsignedTinyInteger('status_foodentry')->nullable();
            $table->time('hs')->nullable();
            $table->unsignedTinyInteger('status_out')->nullable();
            $table->foreignId('worker_id')->constrained('workers')->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assists');
    }
};
