<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkAdminPermission');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $logged = Auth::user();
        
        $user = User::where('id', $id)->first();
        

        if(!$user){
            return redirect()->back()->with("error", "Usuário não cadastrado.");
        }
       
        $data = [
            'user'    => $user,
        ];

        return view('cms.user_form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $logged = Auth::user();
        
        $user = User::where('id', $id)->first();
        

        $data = $request->only([
            'name',
        ]);
        $data['emailActive'] = false;
        $data['passwordActive'] = false;

        
        if($request['email'] != $user->email){
            
            $data['email'] = $request['email'];
            $data['emailActive'] = true;
        }
        
        if($request->input('password')){
            
            $data['password'] = $request->input('password');
            $data['password_confirmation'] = $request->input('password_confirmation');
            $data['passwordActive'] = true;
            
        }
        
        $this->validator($data);

        if($request->input('password')){
        
            $data['password'] = Hash::make($data['password']);
        }
        
        $user->update($data);
        
        return redirect()->back()->with("success", "Usuário <strong>$user->name</strong> foi atualizado com sucesso.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }

    public function validator($data)
    {
        Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['exclude_if:emailActive,false', 'required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['exclude_if:passwordActive,false', 'required', 'confirmed', 'min:8'],
        ])->validate();
    }
}
