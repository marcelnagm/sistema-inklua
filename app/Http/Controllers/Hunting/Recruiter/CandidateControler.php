<?php

namespace App\Http\Controllers\Hunting\Recruiter;

use App\Models\CandidateHunting as Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\InkluaUser;
use Illuminate\Support\Facades\Auth;

class CandidateControler extends Controller {


    /**
     *   Retorna um Json com todos os registos
     * @return Json 
     */
    public function index() {
        return Candidate::orderBy('id')->cursorPaginate(10);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
           $user = auth()->guard('api')->user();
        if (InkluaUser::isInternal($user->id)) {
            return Candidate::where('gid', $id)->first()->compact();
        } else {
            return response()->json([
                        'status' => false,
                        'msg' => 'Função apenas para recrutadores internos',
            ]);
        }
    }


}
