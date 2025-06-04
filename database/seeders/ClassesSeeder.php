<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('classes')->insert([
            [
                'nom' => 'Classe',
                'niveau' => '1',
                'serie' => 'D',
            ],

            [
                'nom' => 'Classe',
                'niveau' => '2',
                'serie' => 'AB',
            ],

            [
                'nom' => 'Classe',
                'niveau' => '3',
                'serie' => '',
            ],

            [
                'nom' => 'Classe',
                'niveau' => '4',
                'serie' => '',
            ],

            [
                'nom' => 'Classe',
                'niveau' => '5',
                'serie' => '',
            ],
        ]);
    }
}
