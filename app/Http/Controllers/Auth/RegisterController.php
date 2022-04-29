<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{

    protected function validator(array $data)
    {
        $messages = array(
            'accepted_terms.required' => 'Você precisa aceitar os termos de uso e a política de privacidade para criar uma conta',
            'accepted_terms.in' => 'Você precisa aceitar os termos de uso e a política de privacidade para criar uma conta'
        );

        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'accepted_terms' => ['required', 'in:true'],
        ], $messages);
    }

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
        
        // Fire off the internal request. 
        $proxy = Request::create(
            'oauth/token',
            'POST'
        );

        return $proxy;
    }

    public function register(Request $request)
    {
        $data = request()->only('email','name', 'lastname', 'password', 'password_confirmation', 'accepted_terms');
        $data['accepted_terms'] = $data['accepted_terms'] === true ? "true" : false;

        $validator = $this->validator($data);

        if ($validator->fails()) {
            return response()->json(
                [
                    "errors" => $validator->messages()
                ], 400
            );
        }

        $user = User::create([
            'name' => $data['name'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'accepted_terms' => 1,
            'has_password' => 1
        ]);

        $user->email_verified_at = NULL;
        $user->getWallet();

        event( new Registered($user) );

        return response()->json(
            [
                "user" => $user,
                "token" => $user->createToken(NULL)->accessToken
            ]
        );
    }
}
