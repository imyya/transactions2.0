<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CompteController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/transactions/storee',[TransactionController::class,'store']);
Route::get('/clients',[ClientController::class,'index']);
Route::get('/clients/comptes',[ClientController::class,'clientsAccounts']);
Route::get('/clients/noaccs',[ClientController::class,'clientsNoAccount']);
Route::post('/clients/add',[ClientController::class,'store']);
Route::get('/clients/{id}',[ClientController::class,'find']);
Route::get('/transactions',[TransactionController::class,'index']);
Route::post('/transactions/depot',[TransactionController::class,'depot']);
Route::post('/transactions/transfert',[TransactionController::class,'transfert']);
Route::post('/comptes/store',[CompteController::class,'store']);
Route::post('/transactions/filter',[TransactionController::class,'filter']);
Route::get('/transactions/comptes/{id}',[TransactionController::class,'transactionsByAccount']);
// Route::put('/transactions/cancel/{id}',[TransactionController::class,'cancel']);
Route::put('/transactions/cancel/{id}',[TransactionController::class,'cancelLast']);
Route::put('/comptes/block',[CompteController::class,'toggleBlock']);
Route::put('/comptes/deactivate',[CompteController::class,'deactivate']);
Route::put('/transactions/state',[TransactionController::class,'updateState']);












