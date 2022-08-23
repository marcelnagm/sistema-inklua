<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Content;
use App\Jobs\BoletoVerify;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Log\Logger;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller {

    public function __construct() {
        $this->middleware('auth:api');
    }

    public function create(Request $request) {
        $user = Auth::user();
        if ($user == null)
            $user = auth()->guard('api')->user();

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
            logger('passo4');
            return response()->json([
                        "msg" => 'Vaga encontrada mas o status da vaga é: ' . $position->status,
                        'error' => true,
                        'status' => false
            ]);
        }


        $transaction = Transaction::create([
                    'content_id' => $position->id,
        ]);

//        try {

        $pagarme = json_decode($transaction->createOrder(Transaction::getCustomer($user), Transaction::getPayments(), $user, $position), true);

        logger('----resposta --- pagarme');
        logger($pagarme);
        if ($request->exists('dump1'))
            dd($$pagarme);
//        } catch (Exception $erros) {
//          

        logger('----transaction registrada');
        logger($transaction);

        if ($pagarme['status'] == 'paid') {
            $transaction->updateFromGateway($pagarme);
            $position->update(['status' => 'publicada', 'published_at' => Carbon::now()->format('Y-m-d')]);
//                $position->notifyPositionPublished();
//            logger('passo8');
            return response()->json([
                        'url' => $transaction->url,
                        'pagarme' => $pagarme['status']
                            ], 200);
        }
        $transaction->updateFromGateway($pagarme);

        if ($request->exists('dump2'))
            dd($transaction);
//        } catch (Exception $erros) {
//          

        return response()->json([
                    'url' => $transaction->url,
                    'pagarme' => $pagarme['status']
                        ], 200);
    }

    public function order() {
        BoletoVerify::dispatch();
        return;
    }

    public function paid(Request $request) {
        $transaction = Transaction::where('content_id', $request->input('content_id'))
                ->where('status', 'paid');

        if ($transaction->count() > 0) {
            return response()->json([
                        'error' => false,
                        'status' => true,
                        'paid' => true,
                        "msg" => 'Vaga Paga'
            ]);
        } else {
            return response()->json([
                        'error' => true,
                        'status' => false,
                        'paid' => false,
                        "msg" => 'Vaga Pendente'
            ]);
        }
    }

}
