<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Content;
use App\Jobs\BoletoVerify;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function create(Request $request)
    {
        $user = $request->user();
        $position = Content::where('id', $request->input('content_id'))
                                ->where('type', 1)
                                ->whereNotNull('user_id')
                                ->where('status', 'aguardando_pagamento')
                                ->with('user')
                                ->first();
                                
        if(!$position){
            return response()->json(["error" => 'Vaga não encontrada.'], 400);
        }

        $transaction = Transaction::create([
            'content_id' => $position->id,            
        ]);

        try {

            $pagarme = json_decode($transaction->createOrder(Transaction::getCustomer($user), Transaction::getPayments()), true);
            if(!isset($pagarme["id"])){
                return response()->json($pagarme);
            }
            $transaction->updateFromGateway($pagarme);

            if($transaction->status == 'paid') {
                $position->update(['status' => 'publicada', 'published_at' => Carbon::now()->format('Y-m-d')]);
                $position->notifyPositionPublished();
            }

            return response()->json($transaction);

        }catch(Exception $erros){
            return response()->json(
                [
                    "code" => "MESSAGE_NOT_SENT",
                    "errors" => $pagarme
                ], 400
            );
        }

    }

    public function order(){
         BoletoVerify::dispatch();
        return;
    }
}
