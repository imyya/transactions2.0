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

    public function find($id){
        $client= Client::where('id',$id)->first();
        return response()->json($client);
    }
}
