<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PeriodeAcademique;


class PeriodeAcademiqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        PeriodeAcademique::create([
            'nom' => '1',
            'date_debut' => '2025-01-01',
            'date_fin' => '2025-03-01',
            'annee_academique_id' => 1
        ]);

           PeriodeAcademique::create([
            'nom' => '2',
            'date_debut' => '2025-03-02',
            'date_fin' => '2025-06-01',
            'annee_academique_id' => 1
        ]);
    }
}

