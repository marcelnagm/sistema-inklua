<?php

namespace App\Http\Controllers\HuntingAdmin;


use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('type','PJ')->paginate();

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
                        . ") ")->where('type','PJ')->
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
    public function show($id)
    {
        $user = User::find($id);

        return view('cms.hunting-admin.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
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
    public function update(Request $request, User $user)
    {
        request()->validate(User::$rules);

        $user->update($request->all());

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

}
