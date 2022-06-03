<?php

namespace App\Http\Controllers\HuntingAdmin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\InkluaUser;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $users = User::where('type', 'PJ')
                ->orderBy('created_at', 'desc')
                ->paginate();

        return view('cms.hunting-admin.user.index', compact('users'))
                        ->with('i', (request()->input('page', 1) - 1) * $users->perPage());
    }

    public function clear(Request $request) {
        $request->session()->forget('recruiter');
//        dd('oeg');
        return redirect()->route('users.index');
//    dd('')
    }

    public function search(Request $request) {

        if ($request->has('page')) {
            $param = $request->session()->get('hunt')['param'];
        } else {
            $param = $request->input('search');

//        dd($gender,$race);
            session([
                'recruiter' => array(
                    'param' => $param
                )
            ]);
        }

        $users = User::
                whereRaw("("
                        . "name like '%$param%'  or "
                        . "lastname like '%$param%'  or "
                        . "phone like '%$param%'  or "
                        . "email like '%$param%'  "
                        . ") ")->where('type', 'PJ')->
                orderby('created_at', 'DESC');

//        dd($this->getEloquentSqlWithBindings($candidates));
        $users = $users->paginate();

        return view('cms.hunting-admin.user.index', compact('users'))
                        ->with('i', (request()->input('page', 1) - 1) * $users->perPage());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $user = User::find($id);

        return view('cms.hunting-admin.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $user = User::find($id);

        return view('cms.hunting-admin.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function promote(Request $request, $id) {
        $user = User::find($id);
        $office = \App\Models\InkluaOffice::where('active',1)->get();
        $role = \App\Models\OfficeRole::all();
        return view('cms.hunting-admin.user.promote', compact('user', 'office','role'));
    }

    public function grant(Request $request, $id) {
//        dd($request);        
        $user = User::find($id);
        if (!$user->isInklua() ) {
            
       
        if(in_array($request->input('role_id'), array(1,2))){                        
            $ink = InkluaUser::
                    where('office_id',$request->input('office_id'))->
                    where('role_id',$request->input('role_id') )->
                    where('active',1)->first();
            if($ink != null){
                $name = $ink->user()->fullname().' - INKLUER#'.$ink->user()->id ;
               return redirect('users/' . $user->id)
                        ->with('error', "Já existe um lider/PFL para este escritorio, o $name  deve ser revogado para esta acao continuar");         
            }
        }
            $user->promote($request);
            
            
            return redirect('users/' . $user->id)->with('success', 'Usuario atribuido a um escritorio'); 
        } else {               
           return redirect()->route('users.index')
                        ->with('success', 'Usuario atribuido a um escritorio');
    
        }
    }

    public function revoke(Request $request, $id) {
        $user = User::find($id);
//        dd($user);
        $user->revoke();
        return redirect('users/' . $user->id)->with('success', 'Usuario removido de um escritorio');;
    }

    public function update(Request $request, User $user) {
        request()->validate(User::$rules);

        $user->update($request->all());

        return redirect()->route('users.index')
                        ->with('success', 'User updated successfully');
    }

}
