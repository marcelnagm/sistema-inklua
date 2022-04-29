<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use DB;

class ResetPasswordController extends Controller
{


    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    public function validateReset(Request $request) 
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email'
        ], $this->validationErrorMessages());

        $credentials =  $request->only('email', 'token');

        if (is_null($user = $this->broker()->getUser($credentials))) {
            return response()->json(
                [
                    "message" => "Token inválido.",
                ], 400
            );
        }

        if (!$this->broker()->tokenExists($user, $credentials['token'])) {
            return response()->json(
                [
                    "message" => "Token inválido.",
                ], 400
            );
        }

        return response()->json(
            [
                "message" => "Token válido.",
            ], 200
        );
    }

    public function reset(Request $request)
    {
        $request->validate($this->rules(), $this->validationErrorMessages());

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response == Password::PASSWORD_RESET
                    ? $this->sendResetResponse($request, $response)
                    : $this->sendResetFailedResponse($request, $response);
    }

    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ];
    }

    protected function validationErrorMessages()
    {
        return [];
    }

    protected function credentials(Request $request)
    {
        return $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );
    }

    protected function resetPassword($user, $password)
    {
        $this->setUserPassword($user, $password);

        $user->setRememberToken(Str::random(60));

        $user->save();

        event(new PasswordReset($user));

        $this->guard()->login($user);
    }

    protected function setUserPassword($user, $password)
    {
        $user->password = Hash::make($password);
    }

    public function broker()
    {
        return Password::broker();
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }



    protected function sendResetResponse(Request $request, $response)
    {
        $user = $this->broker()->getUser($this->credentials($request));
        $user->getWallet();
        return response()->json(
            [
                "message" => "Sua senha foi alterada com sucesso.",
                "user" => $user,
                "token" => $user->createToken(NULL)->accessToken,
            ], 200
        );
        
        return redirect($this->redirectPath())
                            ->with('status', trans($response));
    }

    protected function sendResetFailedResponse(Request $request, $response)
    {
        return response()->json(
            [
                "errors" => [ trans($response) ]
            ], 400
        );

    }
    

}
