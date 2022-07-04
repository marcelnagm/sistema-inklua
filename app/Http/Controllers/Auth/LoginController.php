<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class LoginController extends Controller
{

    protected function token(array $data)
    {
        $client = \Laravel\Passport\Client::where('password_client', 1)->first();

        request()->request->add([
            'grant_type'    => 'password',
            'client_id'     => $client->id,
            'client_secret' => $client->secret,
            'username'      => $data['email'],
            'password'      => $data['password'],
            'scope'         => null,
        ]);
        
        $proxy = Request::create(
            'oauth/token',
            'POST'
        );

        return $proxy;
    }


    public function login(Request $request)
    {
        $data = request()->only('email','password');
        $user = User::where('email', $request->email)->first();

        if ($user &&
            Hash::check($request->password, $user->password)) {

            $user->getWallet();
            
            $user->last_login_at = Carbon::now();
            $user->save();
            return response()->json(
                [
                    "user" => $user,
                    "token" => $user->createToken(NULL)->accessToken
                ]
            );
        }

        return response()->json(
            [
                "message" => "Usuário ou senha inválidos"
            ], 400
        );
    }
}
