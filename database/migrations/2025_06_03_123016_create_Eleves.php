<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eleves', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->string('prenom', 100);
            $table->string('telephone', 10);
            $table->string('photo');
            $table->string('email')->unique();
            $table->string('mot_de_passe');
            $table->date('date_naissance');
            $table->foreignId('classe_id')->constrained('classes');
            $table->foreignId('annee_academique_id')->constrained('annee_academique');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eleves');
    }
};
