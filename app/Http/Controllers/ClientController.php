<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Resources\ClientResource;
use Illuminate\Support\Facades\Validator;
use Egulias\EmailValidator\Result\ValidEmail;
use Illuminate\Validation\ValidationException;

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
            $q->select('id','client_id', 'acc_number','activated','blocked');
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
                    'numero_compte' => $compte->acc_number,
                    'activated'=>$compte->activated,
                    'blocked'=>$compte->blocked
                ];
            }
        }
        return response()->json($result);
    }

    public function store(Request $request){
      $validated = Validator::make($request->all(),[
        'tel' => ['unique:clients','required','regex:/^(77|70|76)\d{7}$/'],
        'firstname' => 'required',
        'lastname' => 'required',
    ], ['tel.unique'=>'Ce numero de telephone existe deja',
        'tel.regex' => 'Le numéro de téléphone doit commencer par "77", "70", "78" ou "76" suivi oar 6 chiffres ',
       // 'tel.digits_between' => 'Le numéro de téléphone doit contenir 9 chiffres.',
    ]);

    if ($validated->fails()) {
        throw new ValidationException($validated);
    }


    $client = Client::create(['lastname'=>$request->lastname, 'firstname'=>$request->firstname, 'tel'=>$request->tel]);
    return response()->json($client);
      
    }

   


}
