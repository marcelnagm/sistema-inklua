<?php

namespace App\Http\Controllers\HuntingAdmin;

use App\Models\CandidateHunting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CandidateGender;
use App\Models\CandidateRace;
use App\Models\CandidateEnglishLevel;
use App\Models\State;
use App\Http\Controllers\Hunting\Recruiter\CandidateControler as RecruiterCandidateController;
use Illuminate\Support\Facades\Storage;

/**
 * Class CandidateHuntingController
 * @package App\Http\Controllers
 */
class CandidateController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $candidateHuntings = CandidateHunting::paginate();
        $race = CandidateRace::orderBy('id')->get();
        $gender = CandidateGender::orderBy('id')->get();

        return view('cms.hunting-admin.candidate.index', compact('candidateHuntings', 'race', 'gender'))
                        ->with('i', (request()->input('page', 1) - 1) * $candidateHuntings->perPage());
    }

    public function clear(Request $request) {
        $request->session()->forget('hunt');
//        dd('oeg');
        return redirect()->route('hunt.index');
//    dd('')
    }

    public function search(Request $request) {

        if ($request->has('page')) {
            $param = $request->session()->get('hunt')['param'];
            $race = $request->session()->get('hunt')['race'];
            $gender = $request->session()->get('hunt')['gender'];
        } else {
            $param = $request->input('search');
            $race = $request->input('race_id');
            $gender = $request->input('gender_id');
//        dd($gender,$race);
            session([
                'hunt' => array(
                    'param' => $param,
                    'race' => $race,
                    'gender' => $gender
                )
            ]);
        }

        $candidateHuntings = CandidateHunting::
                whereRaw("("
                        . "name like '%$param%'  or "
                        . "surname like '%$param%'  or "
                        . "cellphone like '%$param%'  or "
                        . "id = '$param'  "
                        . ") ")->
                orderby('created_at', 'DESC');

        if ($race != 0) {
            $candidateHuntings->where('race_id', $race + 1);
        }
        if ($gender != 0) {
            $candidateHuntings->where('gender_id', $gender + 1);
        }
//        dd($this->getEloquentSqlWithBindings($candidates));
        $candidateHuntings = $candidateHuntings->paginate();

        $races = CandidateRace::orderBy('id')->get();
        $genders = CandidateGender::orderBy('id')->get();

        return view('cms.hunting-admin.candidate.index', compact('candidateHuntings', 'races', 'genders'))
                        ->with('i', (request()->input('page', 1) - 1) * $candidateHuntings->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

        $race = CandidateRace::orderBy('id')->get();
        $gender = CandidateGender::orderBy('id')->get();
        $english_levels = CandidateEnglishLevel::all();
        $states = State::all();
        $pcd = \App\Models\PcdType::all();
        $candidateHunting = new CandidateHunting();
        return view('cms.hunting-admin.candidate.create', compact('candidateHunting', 'race', 'pcd', 'gender', 'english_levels', 'states'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

//        dd($request);
        request()->validate(CandidateHunting::$rules);

        $candidateHunting = new CandidateHunting($request->all());
        $candidateHunting->generate();
        if ($request->file('pcd_report') != NULL) {
            $pcd_report = file_get_contents($request->file('pcd_report')->getRealPath());
            $candidateHunting->save_pcd_report(base64_encode($pcd_report), $request->file('pcd_report')->extension());
        }
        if ($request->file('cv_path') != NULL) {
            unset($pcd_report);
            $pcd_report = file_get_contents($request->file('cv_path')->getRealPath());
            $candidateHunting->save_cv_path(base64_encode($pcd_report), $request->file('cv_path')->extension());
        }
        $candidateHunting->save();
//    dd($request);
        return redirect()->route('candidate-hunt.show', array('candidate_hunt' => $candidateHunting))
                        ->with('success', 'Candidato editado com sucesso');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $candidateHunting = CandidateHunting::find($id);

        return view('cms.hunting-admin.candidate.show', compact('candidateHunting'));
    }

    public function cv($id) {

        return (new RecruiterCandidateController())->cv($id);
    }

    public function pcd_report($id) {        
        return (new RecruiterCandidateController())->pcd_report($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $race = CandidateRace::orderBy('id')->get();
        $gender = CandidateGender::orderBy('id')->get();
        $english_levels = CandidateEnglishLevel::all();
        $states = State::all();
        $pcd = \App\Models\PcdType::all();

        $candidateHunting = CandidateHunting::find($id);

        return view('cms.hunting-admin.candidate.edit', compact('candidateHunting', 'race', 'pcd', 'gender', 'english_levels', 'states'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  CandidateHunting $candidateHunting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
//        dd($request->file());
        request()->validate(CandidateHunting::$rules);

   

        $candidateHunting = CandidateHunting::where('gid', $request->input('gid'))->first();
        $data = $request->all();

        if ($request->file('pcd_report') != NULL) {
            $pcd_report = file_get_contents($request->file('pcd_report')->getRealPath());
            $candidateHunting->save_pcd_report(base64_encode($pcd_report), $request->file('pcd_report')->extension());                 
            unset($data['pcd_report']);
        }
        if ($request->file('cv_path') != NULL) {
            $pcd_report = file_get_contents($request->file('cv_path')->getRealPath());
            $candidateHunting->save_cv_path(base64_encode($pcd_report), $request->file('cv_path')->extension());
            unset($data['cv_path']);
        }
//        $candidateHunting->save_cv_path(base64_encode($request->file('cv_path')), $request->file('cv_path')->extension());        
       $candidateHunting->update($data);
        
        return redirect()->route('candidate-hunt.show', array('candidate_hunt' => $candidateHunting))
                        ->with('success', 'Candidato editado com sucesso');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id) {
        $candidateHunting = CandidateHunting::find($id)->delete();

        return redirect()->route('hunt.index')
                        ->with('success', 'Candidato removido com sucesso');
    }

}
