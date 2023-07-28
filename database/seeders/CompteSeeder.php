<?php

namespace Database\Seeders;

use App\Models\Compte;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CompteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $comptes = [
            ['client_id' => 1, 'solde' => '5000', 'fournisseur' => 'OM'],
            ['client_id' => 2, 'solde' => '5000', 'fournisseur' => 'WV'],
            ['client_id' => 3, 'solde' => '5000', 'fournisseur' => 'WR'],
            ['client_id' => 4, 'solde' => '5000', 'fournisseur' => 'CB'],
            ['client_id' => 5, 'solde' => '5000', 'fournisseur' => 'OM'],
            ['client_id' => 6, 'solde' => '5000', 'fournisseur' => 'WV']
        ];

        Compte::insert($comptes);
    }
}
