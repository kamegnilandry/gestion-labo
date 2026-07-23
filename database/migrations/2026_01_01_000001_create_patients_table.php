<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('code_patient')->unique();
            $table->string('nom');
            $table->string('prenom');
            $table->string('sexe', 1);
            $table->date('date_naissance');
            $table->string('telephone');
            $table->string('adresse')->nullable();
            $table->string('email')->nullable();
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['nom', 'prenom']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
