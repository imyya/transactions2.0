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
            ['lastname' => 'Diallo', 'firstname' => 'Amadou', 'tel' => '77 123 45 67'],
            ['lastname' => 'Sarr', 'firstname' => 'Mariam', 'tel' => '76 123 45 67'],
            ['lastname' => 'Gueye', 'firstname' => 'Babacar', 'tel' => '70 123 45 67'],
            ['lastname' => 'Ndiaye', 'firstname' => 'Khady', 'tel' => '77 123 45 78'],
            ['lastname' => 'Diop', 'firstname' => 'Cheikh', 'tel' => '77 345 67 89'],
            ['lastname' => 'Fall', 'firstname' => 'Aissatou', 'tel' => '77 518 76 67'],
            ['lastname' => 'Diop', 'firstname' => 'Awa', 'tel' => '77 518 67 76'],
            ['lastname' => 'Sow', 'firstname' => 'Mamadou', 'tel' => '76 518 76 67'],
            ['lastname' => 'Ndiaye', 'firstname' => 'Fatou', 'tel' => '77 234 56 78'],
        ];

        Client::insert($clients);
    }
}
