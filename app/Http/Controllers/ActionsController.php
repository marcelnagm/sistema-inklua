<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Models\Content;
use App\Models\Wallet;
use App\Models\Action;

class ActionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    protected function validateShare(array $data)
    {
        return Validator::make($data, [
            'action' => ['required', Rule::in(['share', 'click'])],
            'media' => ['required', Rule::in(['whatsapp', 'facebook', 'linkedin', 'twitter', 'ad'])],
            'content_id' => ['required', 'integer']
        ]);
    }

    protected function validateClick(array $data)
    {
        return Validator::make($data, [
            'action' => ['required', Rule::in(['click'])],
            'media' => ['required', Rule::in(['ad'])],
            'content_id' => ['required', 'integer']
        ]);
    }

    public function share(Request $request)
    {
        $user = $request->user();
        $data = $request->only(['action', 'media', 'content_id']);

        $validator = $this->validateShare($data);

        if($validator->fails()){
            return response()->json(
                [
                    "errors" => $validator->messages()
                ], 400
            );
        }

        if(!Content::where("id", $request->content_id)->exists()){
            return response()->json(
                [
                    "errors" => "Conteúdo não encontrado"
                ], 400
            );
        }

        $wallet = $user->getWallet();

        if(Action::where("wallet_id", $wallet->id)
            ->where("content_id", $request->content_id)
            ->where("action", $request->action)
            ->where("media", $request->media)
            ->exists()
        ){
            return response()->json(
                [
                    "errors" => "Ação já realizada"
                ], 400
            );
        }

        $coins = ($request->media == 'ad') ? 300 : 40;

        $data = [
            "wallet_id" => $wallet->id,
            "content_id" => $request->content_id,
            "action" => $request->action,
            "media" => $request->media,
            "coins" => $coins
        ];

        Action::create($data);
        $wallet->coins += $data["coins"];
        $wallet->save();

        return response()->json([
            'message' => "Ação realizada",
            'data' => [
                'coins' => $data["coins"]
            ]
        ]);
    }

    public function click(Request $request)
    {
        $user = $request->user();
        $data = $request->only(['action', 'media', 'content_id']);

        $validator = $this->validateClick($data);

        if($validator->fails()){
            return response()->json(
                [
                    "errors" => $validator->messages()
                ], 400
            );
        }

        if(!Content::where("id", $request->content_id)->where("type", 2)->exists()){
            return response()->json(
                [
                    "errors" => "Anúncio não encontrado"
                ], 400
            );
        }

        $wallet = $user->getWallet();

        if(Action::where("wallet_id", $wallet->id)
            ->where("content_id", $request->content_id)
            ->where("action", $request->action)
            ->where("media", $request->media)
            ->exists()
        ){
            return response()->json(
                [
                    "errors" => "Ação já realizada"
                ], 400
            );
        }

        $data = [
            "wallet_id" => $wallet->id,
            "content_id" => $request->content_id,
            "action" => $request->action,
            "media" => $request->media,
            "coins" => 300
        ];

        Action::create($data);
        $wallet->coins += $data["coins"];
        $wallet->save();

        return response()->json([
            'message' => "Ação realizada",
            'data' => [
                'coins' => $data["coins"]
            ]
        ]);
    }


    public function donate(Request $request){
        $user = $request->user();
        $wallet = $user->getWallet();

        if($wallet->coins <= 0){
            return response()->json(
                [
                    "errors" => "Você não tem saldo para doar."
                ], 400
            );
        }

        $donation = $wallet->coins;

        $data = [
            "wallet_id" => $wallet->id,
            "action" => "Doou",
            "coins" => -$donation
        ];
        Action::create($data);

        $wallet->coins = 0;
        $wallet->save();
        return response()->json([
            'message' => "Você doou {$donation} Inkoins"
        ]);
    }

    public function clear(Request $request){

        if(App::environment() != 'local' && App::environment() != 'dev'){
            return response()->json([
                'message' => "Ação indisponível neste ambiente"
            ], 400);
        }

        $user = $request->user();
        $wallet = $user->getWallet();

        $wallet->actions()->delete();
        $wallet->coins = 0;
        $wallet->save();

        return response()->json([
            'message' => "Limpou ações e zerou moedas"
        ]);
    }
}
