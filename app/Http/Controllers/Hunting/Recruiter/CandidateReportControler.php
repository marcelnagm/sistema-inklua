<?php

namespace App\Http\Controllers\Hunting\Recruiter;

use App\Models\CandidateReport;
use App\Models\CandidateHunting as Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \App\Models\User;
use App\Models\InkluaUser;
use App\Http\Controllers\Controller;
use App\Models\ContentClient;

class CandidateReportControler extends Controller {

    /**
     *   Retorna um Json com todos os registos
     * @return Json 
     */
    public function index(Request $request) {
        $user = auth()->guard('api')->user();
        if ($user->isInklua()) {
            return array('data' => ["candidates" => Candidate::when($request->exists('candidate_id'), function ($query) {
                        return $query->where('id', request('candidate_id'));
                    })->when(!$request->exists('candidate_id'), function ($query) {
                        return $query->where('id', -1);
                    })->get()
                    , 'reports' => CandidateReport::when($request->exists('candidate_id'), function ($query) {
                        return $query->where('candidate_id', request('candidate_id'));
                    })->when($request->exists('job_id'), function ($query) {
                        return $query->where('job_id', request('job_id'));
                    })->orderBy('updated_at', "DESC")->get()]);
        } else {
            return response()->json([
                        'status' => false,
                        'error' => true,
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

//        dd ($data);
//	$validator = Validator::make(Input::all(), $rules,$messsages);
        if ($request->exists('id')) {

            $report = CandidateReport::find($request->input('id'));
            $cand = $report->candidate();
        } else
            $cand = Candidate::find($data['candidate_id']);
        if ($cand == null) {
            return response()->json([
                        'status' => false,
                        'error' => true,
                        'msg' => 'Candidato não encontrado!',
            ]);
        }
        if ($request->exists('id')) {
            $cont = $report->content()->contentclient();
        } else
            $cont = ContentClient::where('content_id', $data['job_id'])->first();
        if ($cont == null) {
            return response()->json([
                        'status' => false,
                        'error' => true,
                        'msg' => 'Associação com cliente não criada, falta a informação da vaga de que cliente esta associado, posição e condições do cliente!',
            ]);
        }
        if (!$cont->hasVacancy() && $cont == null)
            return response()->json([
                        'status' => false,
                        'error' => true,
                        'msg' => 'Não existem vagas disponiveis[vagas - ' . $cont->vacancy . ',contratados - ' . $cont->vacancy . ',repostos - ' . $cont->replaced . '] para a vaga  escolhida!',
            ]);
//   
        if ($cand->status == 0 || $cand->status == null || $cand->status != -1) {
            if ($cand->status != -1 && $cand->status > 0) {
                $user = User::find($cand->status);
                $msg = "Candidate ja sendo abordado por  " . $user->fullname();
            } else
                $msg = '';
            if (isset($data['report_status_id']))
                $data['report_status_id'] = \App\Models\ReportStatus::byStatusFront($data['report_status_id'])->id;
//                dd($data);                $request->input('id')

            if ($request->exists('id')) {
                $cand = CandidateReport::find($request->input('id'));
                $cand->update($data);
            } else {
                $cand = new CandidateReport($data);

                $cand = $cand->save($data);
                $candidate = $cand->candidate();
                $candidate->status = $user->id;
                $candidate->save();
            }

            return response()->json([
                        'status' => true,
                        'error' => false,
                        'msg' => 'Abordagem Iniciada!',
                        'data' => $cand->toArray(),
                        'msg' => $msg
            ]);
        } else {
            return response()->json([
                        'status' => false,
                        'error' => true,
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
//  public function update(Request $request, $id) {
//        $user = auth()->guard('api')->user();
//        $report = CandidateReport::findOrFail($id);
//        if ($report->status != -1) {
//            if ($request->input('hired') == 1) {
//                $data = $this->validate($request, CandidateReport::$rules_hired);
//            } else
//                $data = $this->validate($request, CandidateReport::$rules);
//
//            unset($data['user_id']);
//            $data['user_id'] = $user->id;
//            $data['report_status_id'] = \App\Models\ReportStatus::byStatusFront($data['report_status_id'])->id;
////            dd($data);
//             $report->update($data);
//            if ($data['report_status_id'] == 5) {
//                $candidate = $report->candidate();
//                $candidate->status = -1;
//                $candidate->save();
//            } else {
//                if ($data['report_status_id'] == 6 || $data['report_status_id'] == 7) {
//                    $cont = $report->content()->contentclient();
//                    if ($data['report_status_id'] == 6) {
//                        $data['hired'] = 1;
//                        $report->update($data);
//                        $candidate = $report->candidate();
//                        $candidate->status = -9999;
//                        $candidate->save();
//                        $cont->hired = $cont->hired + 1;
//                        $cont->save();
//                    } else {
//                        if ($data['report_status_id'] == 7) {
//                            $candidate = $report->candidate();
//                            if ($candidate->status != -9999) {
//                                return response()->json([
//                                            'status' => true,
//                                            'msg' => 'Você não pode fazer a reposição de um candidato não contratado!',
//                                ]);
//                            }
//
//                            $report->replacement();
//                            $report->update($data);
//
//                            return response()->json([
//                                        'status' => true,
//                                        'msg' => 'Candidato Reposto!',
//                            ]);
//                        }
//                    }
//                } else {
//                    $candidate = $report->candidate();
//                    $candidate->status = NULL;
//                    $candidate->save();
//                }
//            }
//            $report->update($data);
//
//            return response()->json([
//                        'status' => true,
//                        'msg' => 'Candidate report successfully updated!',
//                        'data' => $report->toArray()
//            ]);
//        } else {
//            return response()->json([
//                        'status' => false,
//                        'msg' => 'Candidate cannot be interview, do not engage!',
//            ]);
//        }
//    }

    /**
     * Remove o candidato especificado
     *     
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {

        return $candidate = CandidateReport::find($id)->delete();
    }

}
