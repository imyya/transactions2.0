<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Compte;
use Illuminate\Http\Request;

class CompteController extends Controller
{
    public function store(Request $request){
        if (Compte::where(['client_id'=> $request->client_id,'provider'=> $request->provider ])
        ->exists()) {
  return response()->json(['Error' => "Ce client a deja un compte chez ce fournisseur"]);
    } 
    else {
        $client_number=Client::where('id',$request->client_id)->first();
        $account = Compte::create($request->all()+[
            'acc_number'=> $request->provider.'_'.$client_number->tel
        ]);
        return $account;
    }
        
    }

    public function toggleBlock(Request $request){
        $account=Compte::find($request->id);
        if($account){
            $account->blocked = !$account->blocked;
            $account->save();
            return response()->json(['message'=>'Blocked/unblocked successfully','account'=>$account]);

        }
        return response()->json(['Error'=>'Account not found']);
        
    }

    public function deactivate(Request $request){
        $account=Compte::find($request->id);
        if($account){
            $account->activated = !$account->activated;
            $account->save();
            return response()->json(['message'=>'Activated/deactivated successfully','account'=>$account]);

        }
        return response()->json(['Error'=>'Account not found']);
    }


}
