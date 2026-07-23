<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demande_analyses', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('statut')->default('enregistree');
            $table->dateTime('date_demande');
            $table->text('notes')->nullable();
            $table->foreignId('validee_par_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('validee_at')->nullable();
            $table->timestamps();

            $table->index('statut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demande_analyses');
    }
};
