<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Content;
use App\Jobs\BoletoVerify;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Log\Logger;

class TransactionController extends Controller {

    public function __construct() {
        $this->middleware('auth:api');
    }

    public function create(Request $request) {
        $user = $request->user();

        $transaction = Transaction::where('content_id', $request->input('content_id'))
                ->where('status', 'paid');

        if ($transaction->count() > 0)
            return response()->json([
                        'error' => true,
                        'status' => false,
                        "msg" => 'A vaga já se encontra paga'
            ]);

        $position = Content::where('id', $request->input('content_id'))
                ->where('type', 1)
                ->whereNotNull('user_id')
                ->with('user')
                ->first();

        if ($position == null) {
            return response()->json([
                        'error' => true,
                        'status' => false,
                        "error" => 'Vaga não encontrada.']);
        }

        if ($position->status != 'aguardando_pagamento') {

            return response()->json([
                        "msg" => 'Vaga encontrada mas o status da vaga é: ' . $position->status,
                        'error' => true,
                        'status' => false
            ]);
        }


        $transaction = Transaction::create([
                    'content_id' => $position->id,
        ]);

        try {

            $pagarme = json_decode($transaction->createOrder(Transaction::getCustomer($user), Transaction::getPayments()), true);
             if (env('PAGARME_DUMP') == 'retorn1')
                dd($pagarme);
            
            if (env('PAGARME_LOGGER')){
                logger('Pagame retorno');
                logger($pagarme);
            }
                
            if (!isset($pagarme["id"])) {
                return response()->json([
                            'status' => false,
                            'error' => true,
                            'msg' => $pagarme]);
            }
            $transaction->updateFromGateway($pagarme);

            if ($transaction->status == 'paid') {
                $position->update(['status' => 'publicada', 'published_at' => Carbon::now()->format('Y-m-d')]);
                $position->notifyPositionPublished();
            }
            if (env('PAGARME_LOGGER')){
                logger('retorno transcation');
                logger($transaction);
            }
            
          if (env('PAGARME_DUMP') == 'retorn2')
               dd($transaction);
            return response()->json([
                            'status'  => true,
                            'error'  => false,
                            'msg'=> $transaction ]);
        } catch (Exception $erros) {
            return response()->json(
                            [
                                'status' => false,
                                'error' => true,
                                "code" => "MESSAGE_NOT_SENT",
                                "errors" => $pagarme
                            ]
            );
        }
    }

    public function order() {
        BoletoVerify::dispatch();
        return;
    }

}
