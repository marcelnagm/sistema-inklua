<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Validator;
use Socialite;
use Exception;
use Auth;

class SocialController extends Controller
{

    // private function returnLogged($user)
    // {
    //     $user->getWallet();
    //     return response()->json(
    //         [
    //             "user" => $user,
    //             "token" => $user->createToken(NULL)->accessToken
    //         ]
    //     );
    // }


    public function login(Request $request, $media)
    {
        try {
            $socialUser = Socialite::driver($media)->userFromToken($request->token);
            $socialColumn = "{$media}_id";
            $user = User::where($socialColumn, $socialUser->id)->first();

            if(!$user){
                $user = User::where("email", $socialUser->email)->first();

                if(!$user){
                    switch($media){
                        case 'google':
                            $name = $socialUser->user["given_name"];
                            $lastname = $socialUser->user["family_name"];
                            break;
                        default:
                            $name = $socialUser->name;
                            $lastname = "";
                            break;
                    }

                    $user = User::create([
                        'email' => $socialUser->email,
                        'name' => $name,
                        'last_name' => $lastname,
                        $socialColumn => $socialUser->id,
                        'password' => bcrypt($socialUser->token),
                        'accept_terms' => 0
                    ]);
                    $user = $user->fresh();
                    $user->markEmailAsVerified();
                }else{
                    if(!$user->email_verified_at){
                        $user->update([
                            'email' => $socialUser->email,
                        ]);
                        $user->markEmailAsVerified();
                    }
                    $user->update([$socialColumn => $socialUser->id]);
                }
            }

            $user->getWallet();
            
            return response()->json(
                [
                    "user" => $user->toArray(),
                    "token" => $user->createToken(NULL)->accessToken
                ]
            );
    
        } catch (Exception $exception) {
            return response()->json(
                [
                    "message" => $exception->getMessage()
                ], 200
            );
        }
    }
}
