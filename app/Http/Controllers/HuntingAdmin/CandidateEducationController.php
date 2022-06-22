<?php

namespace App\Http\Controllers\HuntingAdmin;

use App\Models\CandidateEducation as CandidateEducationHunting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;

/**
 * Class CandidateEducationHuntingController
 * @package App\Http\Controllers
 */
class CandidateEducationController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $candidateEducationHuntings = CandidateEducation::where('candidate_id',$request->input('candidate_id'))
               ->orderByRaw('-end_at ASC, level_education_id DESC')   
                ->paginate();

        return view('cms.hunting-admin.candidate-education.index', compact('candidateEducationHuntings'))
                        ->with('i', (request()->input('page', 1) - 1) * $candidateEducationHuntings->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id) {
        $candidateEducationHunting = new CandidateEducationHunting(array('candidate_id' => $id));
        $level = \App\Models\LevelEducation::all();
        
        return view('cms.hunting-admin.candidate-education.create', compact('candidateEducationHunting','level'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        request()->validate(CandidateEducationHunting::$rules);

        $candidateEducationHunting = new CandidateEducationHunting($request->all());
        $candidateEducationHunting->save();
        return redirect()->route('candidate-hunt.show', array('candidate_hunt' => $candidateEducationHunting->candidate_id))
                        ->with('success', 'Experiência educacional adicionada com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $candidateEducationHunting = CandidateEducationHunting::find($id);

        return view('cms.hunting-admin.candidate-education.show', compact('candidateEducationHunting'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $candidateEducationHunting = CandidateEducationHunting::find($id);
$level = \App\Models\LevelEducation::all();
        return view('cms.hunting-admin.candidate-education.edit', compact('candidateEducationHunting','level'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  CandidateEducationHunting $candidateEducationHunting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        request()->validate(CandidateEducationHunting::$rules);
        $candidateEducationHunting = CandidateEducationHunting::find($id);
        
        $candidateEducationHunting = $candidateEducationHunting->update($request->all());
        
        return redirect()->route('candidate-hunt.show', array('candidate_hunt' => $request->input('candidate_id')))
                        ->with('success', 'Experiência educacional editada com sucesso');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id) {
        $candidateEducationHunting = CandidateEducationHunting::find($id);
        $id = $candidateEducationHunting->candidate_id;
        $candidateEducationHunting->delete();

        return redirect()->route('candidate-hunt.show', array('candidate_hunt' => $id))
                        ->with('success', 'Experiência educacional removida com sucesso');
    }

}
