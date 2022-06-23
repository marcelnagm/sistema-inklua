<?php

namespace App\Http\Controllers\Hunting\Candidate;

use App\Models\CandidateEducation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\checkUserCandidate;

class CandidateEducationControler extends Controller {

    public function __construct() {
        $this->middleware('App\Http\Middleware\checkUserCandidate');
    }

    /**
     *   Retorna um Json com todos os registos
     * @return Json 
     */
    public function index(Request $request) {

        $user = auth()->guard('api')->user();

        return CandidateEducation::where('candidate_id', $user->candidatehunting()->id)
                ->orderByRaw('-end_at ASC, level_education_id DESC')        
                                   
                        ->get();
    }

    /*
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response retorna um json notificando que foi criado ou uma mensagem de erro de validação de campo
     */

    public function store(Request $request) {

        $data = $this->validate($request, CandidateEducation::$rules);
//        dd ($data);
//	$validator = Validator::make(Input::all(), $rules,$messsages);

        $user = auth()->guard('api')->user();
        $cand = new CandidateEducation($data);
        $cand->candidate_id = $user->candidatehunting()->id;
        $cand->save();

        return response()->json([
                    'status' => true,
                    'msg' => 'Candidate Education successfully added!',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        return CandidateEducation::find($id);
    }

    /**
     * Atualiza o candidato informado {id} segue a regra de validação
     * baseado no campo(s) informado(s)
     *  'title' => 'required|max:255',
     *        'role_id' => 'required|max:255',
     *       'payment' => 'required|max:8',
     *       'CID' => 'required|max:244',
     *       'state_id' => 'required|max:2',
     *       'city' => 'required|max:255',
     *       'remote' => 'required|max:1',
     *       'move_out' => 'required|max:1',
     *       'description' => 'required|max:255',
     *       'english_level' => 'required|max:1',
     *       'full_name' => 'required|max:255', 
     *       'cellphone'=> 'required|max:12', 
     *       'email' => 'required|max:255', 
     *       'cv_url' => 'required|max:255' ,
     *       'status_id'=> 'required|max:1'
     *
     * @param  \Illuminate\Http\Request  $request     
     * @return \Illuminate\Http\Response Json com mensagem de sucesso ou mensagem de erro de validação
     */
    public function update(Request $request, $id) {
        $candidate = CandidateEducation::find($id);
        $candidate->update($this->validate($request, CandidateEducation::$rules));

        return response()->json([
                    'status' => true,
                    'msg' => 'Candidate education successfully updated!',
        ]);
    }

    /**
     * Remove o candidato especificado
     *     
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {

        return $candidate = CandidateEducation::find($id)->delete();
    }

}
