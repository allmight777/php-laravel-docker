<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classe_matiere', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classe_id')->constrained();
            $table->foreignId('matiere_id')->constrained();
            $table->integer('coefficient')->default(1);
            $table->foreignId('professeur_id')->constrained('professeurs');
            $table->timestamps();

            $table->unique(['classe_id', 'matiere_id', 'professeur_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classe_matiere');
    }
};
