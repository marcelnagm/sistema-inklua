<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class VerificationController extends Controller
{
    public function verify($user_id, Request $request) {
        if (!$request->hasValidSignature()) {
            return response()->json(["message" => "Token inválido"], 200);
        }
    
        $user = User::findOrFail($user_id);
    
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }
    
        return response()->json(["message" => "E-mail confirmado"]);
    }

    public function resend(Request $request) {
        if (auth()->user()->hasVerifiedEmail()) {
            return response()->json(["message" => "E-mail já verificado"], 400);
        }
    
        auth()->user()->sendEmailVerificationNotification();
    
        return response()->json(["message" => "Um novo e-mail foi enviado para {$request->user()->email}"]);
    }

    public function show(Request $request)
    {
        // user não tem e-mail verificado (admin deve ser validado manualmente)
        Auth::logout();
        return redirect('/admin/login');
    }
}
