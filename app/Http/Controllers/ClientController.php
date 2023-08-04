<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(){
        $clients= Client::all();
        return response()->json(ClientResource::collection($clients));
    }

    public function clientsNoAccount(){
        $clients=Client::doesntHave('comptes')->get();
        return response()->json($clients);
    }

    public function find($id){
        $client= Client::where('id',$id)->first();
        return response()->json($client);
    }

    public function clientsAccounts(){
        $clients = Client::with(['comptes' => function($q) {
            $q->select('id','client_id', 'acc_number');
        }])
        ->select('id','firstname', 'lastname','tel')
        ->get();
        //return $clients;
        $result = [];
        foreach ($clients as $client) {
            foreach($client->comptes as $compte){
                $result[] = [
                    'client_id' => $client->id,  // This is the 'id' of the client
                    'compte_id'=> $compte->id, // This is the 'id' of the compte
                    'prenom' => $client->firstname,
                    'nom' => $client->lastname,
                    'tel'=>$client->tel,
                    'numero_compte' => $compte->acc_number
                ];
            }
        }
        return response()->json($result);
    }

    
}
