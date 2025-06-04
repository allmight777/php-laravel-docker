<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('professeurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->string('prenom', 100);
            $table->string('telephone', 20);
            $table->string('photo');
            $table->string('email')->unique();
            $table->string('mot_de_passe');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('professeurs');
    }
};
