<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demande_examens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demande_analyse_id')->constrained('demande_analyses')->cascadeOnDelete();
            $table->foreignId('examen_id')->constrained('examens')->restrictOnDelete();
            $table->timestamps();

            $table->unique(['demande_analyse_id', 'examen_id'], 'demande_examen_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demande_examens');
    }
};
