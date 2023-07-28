<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            ['nom' => 'Diallo', 'prenom' => 'Amadou', 'numero' => '77 123 45 67'],
            ['nom' => 'Sarr', 'prenom' => 'Mariam', 'numero' => '76 234 56 78'],
            ['nom' => 'Gueye', 'prenom' => 'Babacar', 'numero' => '70 345 67 89'],
            ['nom' => 'Ndiaye', 'prenom' => 'Khady', 'numero' => '77 456 78 90'],
            ['nom' => 'Diop', 'prenom' => 'Cheikh', 'numero' => '76 567 89 01'],
            ['nom' => 'Fall', 'prenom' => 'Aissatou', 'numero' => '70 678 90 12']
        ];

        Client::insert($clients);
    }
}
