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
        
          if ($user == null)
                return response()->json([
                            'status' => false,
                            'error' => true,
                            'msg' => 'Usuário não encontrado',
                ]);
        if ($user->isInklua()) {
            $cand = Candidate::where('gid', $id)->first();
            if ($cand == null)
                return response()->json([
                            'status' => false,
                            'error' => true,
                            'msg' => 'Candidato não encontrado',
                ]);
            $data = Candidate::where('gid', $id)->first()->compact();
            $data['cv_path'] = route('hunt.api.cv', $data['id']);
            $data['pcd_report'] = route('hunt.api.pcd_report', $data['id']);
            return $data;
        } else {
            return response()->json([
                        'status' => false,
                        'error' => true,
                        'msg' => 'Função apenas para recrutadores internos',
            ]);
        }
    }

    public function cv($id) {
        $candidateHunting = Candidate::find($id);
        if ($candidateHunting == null)
            return response()->json([
                        'status' => false,
                        'error' => true,
                        'msg' => 'Candidato não encontrado',
            ]);
        return Storage::disk(env('APP_STORAGE_DOCS'))->download($candidateHunting->cv_path);
    }

    public function pcd_report($id) {
        $candidateHunting = Candidate::find($id);
        if ($candidateHunting == null)
            return response()->json([
                        'status' => false,
                        'error' => true,
                        'msg' => 'Candidato não encontrado',
            ]);
        return Storage::disk(env('APP_STORAGE_DOCS'))->download($candidateHunting->pcd_report);
    }

}
