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
            ['lastname' => 'Diallo', 'firstname' => 'Amadou', 'tel' => '771234567'],
            ['lastname' => 'Sarr', 'firstname' => 'Mariam', 'tel' => '761234567'],
            ['lastname' => 'Gueye', 'firstname' => 'Babacar', 'tel' => '701234567'],
            ['lastname' => 'Ndiaye', 'firstname' => 'Khady', 'tel' => '771234578'],
            ['lastname' => 'Diop', 'firstname' => 'Cheikh', 'tel' => '773456789'],
            ['lastname' => 'Fall', 'firstname' => 'Aissatou', 'tel' => '775187667'],
            ['lastname' => 'Diop', 'firstname' => 'Awa', 'tel' => '775186776'],
            ['lastname' => 'Sow', 'firstname' => 'Mamadou', 'tel' => '765187667'],
            ['lastname' => 'Ndiaye', 'firstname' => 'Fatou', 'tel' => '772345678'],
        ];

        Client::insert($clients);
    }
}
