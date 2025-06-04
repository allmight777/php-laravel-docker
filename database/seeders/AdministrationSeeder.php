<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdministrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('administrateurs')->insert([
            [
                'nom' => 'admin',
                'prenom' => 'admin',
                'telephone' => '0100000000',
                'email' => 'admin@example.com',
                'photo' => 'url',
                'mot_de_passe' => 'admin123',
            ]
        ]);
    }
}
