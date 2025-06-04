<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MatieresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('matieres')->insert([
            [
                'nom' => 'Maths',
                'code' => 'MT'
            ],

            [
                'nom' => 'Français',
                'code' => 'FA'
            ],

            [
                'nom' => 'Physique',
                'code' => 'PH'
            ],

            [
                'nom' => 'Anglais',
                'code' => 'AG'
            ],

            [
                'nom' => 'Sport',
                'code' => 'EPS'
            ],
        ]);
    }
}
