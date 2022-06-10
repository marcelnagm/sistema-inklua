<?php

namespace App\Http\Controllers\Hunting\Recruiter;

use App\Models\CandidateReport;
use App\Models\CandidateHunting as Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \App\Models\User;
use App\Models\InkluaUser;
use App\Http\Controllers\Controller;

class CandidateReportControler extends Controller {

    /**
     *   Retorna um Json com todos os registos
     * @return Json 
     */
    public function index(Request $request) {
        $user = auth()->guard('api')->user();
        if (InkluaUser::isInternal($user->id)) {
            return CandidateReport::where('candidate_id', $request->input('candidate_id'))->orderBy('updated_at', "DESC")->get();
        } else {
            return response()->json([
                        'status' => false,
                        'msg' => 'Função apenas para recrutadores internos',
            ]);
        }
    }

    /*
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response retorna um json notificando que foi criado ou uma mensagem de erro de validação de campo
     */

    public function store(Request $request) {
        $user = auth()->guard('api')->user();
        $data = $this->validate($request, CandidateReport::$rules);
        unset($data['user_id']);
//        $data['user_id'] = $user->id;
//        dd ($user);
//	$validator = Validator::make(Input::all(), $rules,$messsages);

        $cand = Candidate::find($data['candidate_id']);
        if ($cand->status != -1) {
            if ($cand->status == 0) {
                $cand = new CandidateReport($data);

                $cand->save();
                $candidate = $cand->candidate();
                $candidate->status = $user->id;
                $candidate->save();
                return response()->json([
                            'status' => true,
                            'msg' => 'Abordagem Iniciada!',
                ]);
            } else {
                $user = User::find($cand->status);
                return response()->json([
                            'status' => false,
                            'msg' => "Candidate ja sendo abordado por  $user",
                ]);
            }
        } else {
            return response()->json([
                        'status' => false,
                        'msg' => 'Candidate cannot be interview, do not engage!',
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        return CandidateReport::find($id);
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
        $user = auth()->guard('api')->user();
        $cand = CandidateReport::find($id);
        if ($cand->status != -1) {
            $data = $this->validate($request, CandidateReport::$rules);

            unset($data['user_id']);
            $data['user_id'] = $user->id;
            $cand->update($data);
            if ($data['report_status_id'] == 5) {
                $candidate = $cand->candidate();
                $candidate->status = -1;
                $candidate->save();
            } else {
                if ($data['report_status_id'] == 6) {
                    $candidate = $cand->candidate();
                    $candidate->status = 9999;
                    $candidate->save();
                } else {
                    $candidate = $cand->candidate();
                    $candidate->status = NULL;
                    $candidate->save();
                }
            }



            return response()->json([
                        'status' => true,
                        'msg' => 'Candidate report successfully updated!',
            ]);
        } else {
            return response()->json([
                        'status' => false,
                        'msg' => 'Candidate cannot be interview, do not engage!',
            ]);
        }
    }

    /**
     * Remove o candidato especificado
     *     
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {

        return $candidate = CandidateReport::find($id)->delete();
    }

}
