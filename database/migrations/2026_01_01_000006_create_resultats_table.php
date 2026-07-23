<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resultats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demande_examen_id')->unique()->constrained('demande_examens')->cascadeOnDelete();
            $table->string('valeur');
            $table->string('unite')->nullable();
            $table->string('valeur_reference')->nullable();
            $table->string('interpretation')->nullable();
            $table->text('observations')->nullable();
            $table->foreignId('saisi_par_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resultats');
    }
};
