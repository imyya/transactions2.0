<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
     {     // $table->enum("type",[0,1,2]);
    //     $table->string("amount",255);
    //     $table->date('date');
    //     $table->foreignId('account_id')->constrained('comptes')->nullable;
    //     $table->foreignId('sender_id')->constrained('clients')->nullable;            
    //     $table->foreignId('recipient_id')->constrained('clients')->nullable;
    //     $table->string("code",45)->nullable();
    //     $table->boolean("immediate")->default(false);
        $transactions = [
            ['type' => 1, 'amount' => '2000', 'date'=>'2023-04-12','sender_account_id' => 1, 'recipient_account_id'=>3, "sender_id"=>1,"recipient_id"=>null],
            ['type' => 0, 'amount' => '3000', 'date'=>'2023-04-15','sender_account_id' => null,'recipient_account_id'=>2, 'sender_id'=>7,"recipient_id"=>null],//le depot est fait par client 7 qui na pas de compte et fait au client 2 qui a un compte
            ['type' => 2, 'amount' => '2500', 'date'=>'2023-04-12','sender_account_id' => 1, 'recipient_account_id'=>null, 'sender_id'=>null,"recipient_id"=>null],
            ['type' => 1, 'amount' => '1500', 'date'=>'2023-04-11','sender_account_id' => 3, 'recipient_account_id'=>4, 'sender_id'=>null,"recipient_id"=>null],
            ['type' => 1, 'amount' => '3500', 'date'=>'2023-04-12','sender_account_id' => 1, 'recipient_account_id'=>2, 'sender_id'=>null,"recipient_id"=>null],
            ['type' => 0, 'amount' => '1000', 'date'=>'2023-04-16','sender_account_id' => null, 'recipient_account_id'=>2, 'sender_id'=>null,"recipient_id"=>null]
          
        ];

        Transaction::insert($transactions);
    }
}
