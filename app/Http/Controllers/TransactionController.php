<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Compte;
use App\Models\Transaction;
use Carbon\Exceptions\EndLessPeriodException;
use Illuminate\Database\Events\TransactionRolledBack;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use LengthException;

class TransactionController extends Controller
{
    public function store1(Request $request){
       
        
        
        switch($request->type){
            case 1://si c un transfert
                if($request->fournisseur!=='wari'){//cas ou c pas wari
                    Compte::findOrfail($request->compte_id);//rechercher si le client a un compte 

                    $recip = Client::where('numero', $request->recipient)->first();
                    $recipient=$recip->comptes;

                    if(!$request->code && $recipient->isEmpty()){ //ya pas de code ni de destinataire so..
                       return response()->json(['error'=>"Destinataire sans compte et aucun code recu"]);
        
                    }
                    //ya destinataire but client et dest dont have the same the same fournisseur
                    elseif($recipient && $request->fournisseur!== $recipient[0]->fournisseur){
                        return response()->json(['error'=>'Le destinataire na pas ce fournisseur']);
                    }
                    $transaction=Transaction::create(['type'=>$request->type,"date"=>Carbon::now()->format('Y-m-d'),
                        'montant'=>$request->montant,'compte_id'=>$request->compte_id]);
                        return response()->json($transaction);
            
                };
               
               
            
            break;

       
         case 0://cas depot
            $client= Client::where('numero',$request->recipient)->first();//le client na pas de compte et napas mis son propre numero
            if(!$request->compte_id && !$client){
                return response()->json(['error'=>'Le numero est obligatoire pour le depot ']);

            }
            elseif($client){
                $transaction=Transaction::create(['type'=>$request->type,"date"=>Carbon::now()->format('Y-m-d'),
                'montant'=>$request->montant]);
                return response()->json($transaction);
    
            }

        $transaction=Transaction::create(['type'=>$request->type,"date"=>Carbon::now()->format('Y-m-d'),
        'montant'=>$request->montant,'compte_id'=>$request->compte_id]);
        return response()->json($transaction);
        break;

        case 2:
            $recip = Client::where('numero', $request->recipient)->first();
            if(!$recip){
                return response()->json(['error'=>'Numero destinataire inexistant']);
            }
            $recipient=$recip->comptes;

            if($request->fournisseur!=='wari' && $recipient->isEmpty()){
                return response()->json(['Error'=>'Le destinataire doit avoir un compte']);
            }
          $transaction=Transaction::create(['type'=>$request->type,"date"=>Carbon::now()->format('Y-m-d'),
          'montant'=>$request->montant,'compte_id'=>$request->compte_id]);
          return response()->json($transaction);
          break;
        default:
          return response()->json(['error' => 'Invalid type']);
    } 
   }

   public function index(){
    $transacs = Transaction::all();
    return response()->json($transacs);
   }


   public function transactionsByAccount($id){
    $transacs = Transaction::where('sender_account_id', $id)
                           ->orWhere('recipient_account_id', $id)
                           ->get(['id', 'type', 'amount', 'sender_account_id', 'recipient_account_id', 'date','cancelled',]);
    return $transacs;
    $formattedTransactions = $transacs->map(function ($transaction) {
        return [
            'id' => $transaction->id,
            'type' => $transaction->type,
            'amount' => $transaction->amount,
            'date' => $transaction->date,
            'sender' => $transaction->sender_account_id,
            'recipient' => $transaction->recipient_account_id,
            'cancelled'=>$transaction->cancelled
        ];
    });

    return response()->json($formattedTransactions);
   }

   public function storee(Request $request){
    $transaction=Transaction::create(['type'=>$request->type,"date"=>Carbon::now()->format('Y-m-d'),
        'montant'=>$request->montant,'compte_id'=>$request->compte_id]);
        return response()->json($transaction);
   }


public function depot(Request $request){
    


    if($request->sender_account_id==0 && $request->recipient_account_id==0 && $request->sender_id==0 && $request->recipient_id==0 ){//wari
        $code=$this->generateCode(15);
        $transaction=Transaction::create(['type'=>0,"date"=>Carbon::now()->format('Y-m-d'),
        'amount'=>$request->amount,'sender_account_id'=>null,'recipient_account_id'=>null,'sender_id'=>null,'recipient_id'=>null,'code'=>$code,'immediate'=>$request->immediate,'state'=>false]);
        return response()->json(['code'=>$code]);
    }

    else if($request->sender_account_id==0 && $request->sender_id !=0){
        $transaction=Transaction::create(['type'=>0,"date"=>Carbon::now()->format('Y-m-d'),
        'amount'=>$request->amount,'sender_account_id'=>null,'recipient_account_id'=>$request->recipient_account_id,'sender_id'=>$request->sender_id,'recipient_id'=>null,'code'=>null,'immediate'=>$request->immediate,'state'=>null]);
        $recipientAccount = Compte::find($request->recipient_account_id);

        if (!$recipientAccount) {
            return response()->json(['error' => 'Recipient account not found'], 404);
        }
        $currentBalance = (float) $recipientAccount->balance;

        $depositAmount = (float) $request->amount;
        $newBalance = $currentBalance + $depositAmount;
    
        $recipientAccount->update(['balance' => (string) $newBalance]);
        return response()->json($recipientAccount);
    }

    $transaction=Transaction::create(['type'=>0,"date"=>Carbon::now()->format('Y-m-d'),
    'amount'=>$request->amount,'sender_account_id'=>$request->sender_account_id,'recipient_account_id'=>$request->recipient_account_id,'sender_id'=>null,'recipient_id'=>null,'code'=>null,'immediate'=>$request->immediate]);
    $recipientAccount = Compte::find($request->recipient_account_id);

    if (!$recipientAccount) {
        return response()->json(['error' => 'Recipient account not found'], 404);
    }

    $currentBalance = (float) $recipientAccount->balance;
    $depositAmount = (float) $request->amount;
    $newBalance = $currentBalance + $depositAmount;

    // Convert the new balance back to a string and store it in the database
    $recipientAccount->update(['balance' => (string) $newBalance]);

    return response()->json($transaction);
}

public function transfert(Request $request)
{   $code = $this->generateCode(25);
    $transactionData = [
        'type' => 1,
        'date' => Carbon::now()->format('Y-m-d'),
        'amount' => $request->amount,
        'sender_account_id' => $request->sender_account_id,
        'recipient_account_id' => $request->recipient_account_id,
        'sender_id' => null,
        'recipient_id' => null,
        'code' => null,
        'immediate' => $request->immediate,
        'state' => null
    ];

    if ($request->recipient_id != 0 ) {
        // Si c'est une transaction OM avec code
        $transactionData['recipient_account_id'] = null;
        $transactionData['recipient_id'] = $request->recipient_id;
        $transactionData['code'] = $code;
        $transactionData['state'] = false;

        $senderAccount = Compte::find($request->sender_account_id);
        
        if (!$senderAccount) {
            return response()->json(['error' => 'Sender or recipient account not found'], 404);
        }
        $currentSenderBalance = (float) $senderAccount->balance;
        $transferAmount = (float) $request->amount;
        
        // Mise à jour du solde du compte expéditeur (sender)
        if($currentSenderBalance < $transferAmount){
            return response()->json(['error' => 'Solde insuffisant']);
            
        }
        $newSenderBalance = $currentSenderBalance - $transferAmount;
        $senderAccount->update(['balance' => (string) $newSenderBalance]);
        $transaction = Transaction::create($transactionData);
        return response()->json(['code'=>$code]);

    }

    $senderAccount = Compte::find($request->sender_account_id);

    $recipientAccount = Compte::find($request->recipient_account_id);

    if (!$senderAccount || !$recipientAccount) {
        return response()->json(['error' => 'Sender or recipient account not found'], 404);
    }

    $currentSenderBalance = (float) $senderAccount->balance;
    $currentRecipientBalance = (float) $recipientAccount->balance;
    $transferAmount = (float) $request->amount;

    // Mise a jour du solde du compte expéditeur (sender)
    if($currentSenderBalance < $transferAmount){
        return response()->json(['error' => 'Solde insuffisant']);
    }
    $newSenderBalance = $currentSenderBalance - $transferAmount;

    $senderAccount->update(['balance' => (string) $newSenderBalance]);

    // Mise a jour du solde du compte destinataire (recipient)
    $newRecipientBalance = $currentRecipientBalance + $transferAmount;
    $recipientAccount->update(['balance' => (string) $newRecipientBalance]);
    $transaction = Transaction::create($transactionData);


    return response()->json(['nouvelle_transaction'=>$transaction]);
}


public function retrait(Request $request){
    $transaction=Transaction::create(['type'=>2,"date"=>Carbon::now()->format('Y-m-d'),
    'amount'=>$request->amount,'sender_account_id'=>$request->sender_account_id,'recipient_account_id'=>null,'sender_id'=>null,'recipient_id'=>null,'code'=>null,'immediate'=>false,'state'=>null]);
    $recipientAccount = Compte::find($request->sender_account_id);

    if (!$recipientAccount) {
        return response()->json(['error' => 'Recipient account not found'], 404);
    }
    $currentBalance = (float) $recipientAccount->balance;

    $depositAmount = (float) $request->amount;
    if($currentBalance < $depositAmount){
        return response()->json(['error' => 'Solde insuffisant']);
    }
    $newBalance = $currentBalance - $depositAmount;

    $recipientAccount->update(['balance' => (string) $newBalance]);
    return response()->json(['ok'=>true]);
}



public function cancel($id){
    Transaction::findOrfail($id);
    $cancelled=Transaction::where('id',$id)->update(['cancelled'=>true]);
    return response()->json(["cancelled"=>$cancelled]);
}



public function cancelLast($id){
    $last = Transaction::where('sender_account_id', $id)
    ->whereIn('type', [0, 1])
    ->where('date', '>=', Carbon::now()->subDay())
    ->latest('id')
    ->first();
    if($last->cancelled){
        return response()->json(["error"=>'Transaction already cancelled']);
    }
   $last->update(['cancelled'=>true]);
    return response()->json($last);

}





public function filter(Request $request) {
    $id = $request->input('id');
    $amount = $request->input('amount');
    $date = $request->input('date');

    $query = Transaction::where(function ($query) use ($id) {
        $query->where('sender_account_id', $id);
            // ->orWhere('recipient_account_id', $id);
    });

    // Add additional filters based on the provided parameters
    // if (!is_null($type)) {
    //     $query->where('type', $type);
    // }

    if (!is_null($amount)) {
        $query->where('amount', $amount);
    }

    if (!is_null($date)) {
        $query->where('date', $date);
    }

    $transactions = $query->get();

    return response()->json($transactions);
}


function generateCode($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';

    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $code;
}

public function state(Request $request){
    if(! $request->code){
        return response()->json(['error'=>'Veuillez saisir un code valide']);

    }
    $operation = Transaction::where('code',$request->code)->first();
    //return $operation;
    if(!$operation){
        return response()->json(['error'=>'Aucun depot existant']);

    }
    else if($operation->state==true){
        return response()->json(['error'=>'Somme deja retiree']);
    }

    $operation->update(['state'=>true]);
    return response()->json(['ok'=>true]);
}

public function updateState(Request $request) {
    $code = $request->code;
    $provider = $request->provider;
    $tel = $request->tel;

    // Retrieve client ID based on the provided phone number
    $client = Client::where('tel', $tel)->first();

    if (!$client) {
        return response()->json(['error' => 'Client not found']);
    }

    // Verify the input based on the provider and transaction type
    if ($provider === 'OM') {
        $matchingTransaction = Transaction::where([
            'type'=>1,
            'recipient_account_id' => null,
            'sender_id' => null,
            'recipient_id' => $client->id,
            'code' => $code

        ])->first();

        if (!$matchingTransaction) {
            return response()->json(['error' => "Aucun transfert avec code trouve"]);
        } else {
            if($matchingTransaction->state==true){
                return response()->json(['error' => "Somme deja retiree"]);

            }
            $matchingTransaction->update(['state'=>true]);
            return response()->json(['ok' => 'True']);
        }
    } elseif ($provider === 'WR') {
        $matchingDeposit = Transaction::where([
            'type' => 0,
            'sender_account_id' => null,
            'recipient_account_id' => null,
            'sender_id' => null,
            'recipient_id' => null,
            'code' => $code

        ])->first();

        if (!$matchingDeposit) {
            return response()->json(['error' => "Aucun depot Wari trouve"]);
        } else {
            if($matchingDeposit->state==true){
                return response()->json(['error' => "Somme deja retiree"]);

            }
            $matchingDeposit->update(['state'=>true]);
            return response()->json(['ok' => true]);
        }
    } else {
        return response()->json(['error' => 'Invalid provider or tel']);
    }
}




}
