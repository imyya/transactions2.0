<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Compte;
use App\Models\Transaction;
use Carbon\Exceptions\EndLessPeriodException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TransactionController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'type' => 'required|integer',
            'fournisseur' => 'required|string',
            'compte_id' => 'sometimes|required|integer',
            'recipient' => 'sometimes|required|string',
            'montant' => 'required|numeric',
            'code' => 'sometimes|required|string',
        ]);
        
        
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

}
