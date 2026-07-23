<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prelevements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demande_analyse_id')->unique()->constrained('demande_analyses')->cascadeOnDelete();
            $table->string('type_echantillon');
            $table->foreignId('technicien_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('date_prelevement');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prelevements');
    }
};
