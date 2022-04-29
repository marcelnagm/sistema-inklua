<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function wallet(Request $request)
    {
        $user = request()->user();
        $wallet = $user->getWallet();

        $actions = $wallet->actions()
                        ->orderBy('id', 'desc')
                        ->with(array('content' => function($query) {
                            $query->select('id','title');
                        }))
                        ->limit(10)
                        ->get();

        return response()->json([
            'inkoins' => $wallet->coins,
            'actions' => $actions
        ]);
    }
}
