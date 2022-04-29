<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    protected function validatorUpdate(array $data)
    {
        return Validator::make($data, [
            'name' => ['required'],
            'lastname' => ['required'],
            'password' => ['confirmed','min:6']
        ]);
    }

    protected function validatorChangePasswordWithHasPassword(array $data)
    {
        return Validator::make($data, [
            'current_password' => ['required'],
            'password' => ['required', 'confirmed','min:6']
        ]);
    }

    protected function validatorChangePasswordWithoutHasPassword(array $data)
    {
        return Validator::make($data, [
            'password' => ['required', 'confirmed','min:6']
        ]);
    }

    public function update(Request $request)
    {
        $user = request()->user();
        $data = $request->only(['name', 'lastname']);

        $validator = $this->validatorUpdate($data);

        if( $validator->fails() ){
            return response()->json(
                [
                    "errors" => $validator->messages()
                ], 400
            );
        }
            
        $user->update($data);

        return response()->json([
            'message' => "Conta atualizada",
            'data' => $user
        ]);
    }

    public function updatePassword(Request $request)
    {
        $user = request()->user();
        $data = $request->only(['current_password', 'password', 'password_confirmation']);

        if($user->has_password){
            $validator = $this->validatorChangePasswordWithHasPassword($data);
        }else{
            $validator = $this->validatorChangePasswordWithoutHasPassword($data);
        }

        if( $validator->fails() ){
            return response()->json(
                [
                    "errors" => $validator->messages()
                ], 400
            );
        }

        if($user->has_password && !Hash::check($request->current_password, $user->password)){
            return response()->json(
                [
                    "errors" => ["Sua senha atual está errada"]
                ], 400
            );
        }

        $user->password = Hash::make($request->password);
        $user->has_password = 1;
        $user->save();

        return response()->json([
            'message' => "Senha atualizada",
            'data' => $user
        ]);
    }

    public function acceptTerms(Request $request)
    {
        $user = request()->user();
        $data = array(
            'accepted_terms' => $request->accepted_terms
        );
        $data['accepted_terms'] = $data['accepted_terms'] === true ? "true" : false;

        $validator = Validator::make($data, [
            'accepted_terms' => ['required', 'in:true'],
        ]);

        if( $validator->fails() ){
            return response()->json(
                [
                    "errors" => $validator->messages()
                ]
            );
        }

        $user->update(["accepted_terms" => 1]);
        return response()->json([
            'message' => "Conta atualizada",
            'data' => $user
        ]);
    }

    public function delete(Request $request)
    {
        $user = request()->user();

        if(!Hash::check($request->password, $user->password)) {
            return response()->json(
                [
                    "errors" => "Senha inválida"
                ], 400
            );
        }

        $user->deleteAccount();

        return response()->json(
            [
                "message" => "Conta removida"
            ]
        );
    }
}
