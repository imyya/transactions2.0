<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('type');
            $table->string("amount",255);
            $table->date('date');
            $table->foreignId('sender_account_id')->nullable()->constrained('comptes');
            $table->foreignId('recipient_account_id')->nullable()->constrained('comptes');
            $table->foreignId('sender_id')->nullable()->constrained('clients');            
            $table->foreignId('recipient_id')->nullable()->constrained('clients');
            $table->string("code",45)->nullable();
            $table->boolean("immediate")->default(false);
            $table->boolean("cancelled")->default(false);




            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
