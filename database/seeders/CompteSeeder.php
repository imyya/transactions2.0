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
    { //     $table->id();
    //     $table->foreignId('client_id')->constrained('clients');
    //     $table->string("balance",255);
    //     $table->enum("provider",["OM","WR","CB"]);
    //     $table->string("acc_number");
        $comptes = [
            ['client_id' => 1, 'balance' => '5000', 'provider' => 'OM',"acc_number"=>"OM_771234567"],
            ['client_id' => 2, 'balance' => '35000', 'provider' => 'WV',"acc_number"=>"WV_761234567"],
            ['client_id' => 3, 'balance' => '15000', 'provider' => 'WV',"acc_number"=>"WV_701234567"],
            ['client_id' => 4, 'balance' => '23000', 'provider' => 'CB',"acc_number"=>"CB_771234578"],
            ['client_id' => 5, 'balance' => '2500', 'provider' => 'OM',"acc_number"=>"OM_773456789"],
            ['client_id' => 6, 'balance' => '4600', 'provider' => 'OM',"acc_number"=>"OM_775187667"]
        ];

        Compte::insert($comptes);
    }
}
