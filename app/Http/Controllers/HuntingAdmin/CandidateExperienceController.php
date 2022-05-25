<?php

namespace App\Http\Controllers\HuntingAdmin;

use App\Models\CandidateExperience as CandidateExperienceHunting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class CandidateExperienceHuntingController
 * @package App\Http\Controllers
 */
class CandidateExperienceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $candidateExperienceHuntings = CandidateExperienceHunting::orderBy('start_at','ASC')->paginate();

        return view('cms.hunting-admin.candidate-experience.index', compact('candidateExperienceHuntings'))
            ->with('i', (request()->input('page', 1) - 1) * $candidateExperienceHuntings->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {   
        $candidateExperienceHunting = new CandidateExperienceHunting(array('candidate_id' => $id ));
        return view('cms.hunting-admin.candidate-experience.create', compact('candidateExperienceHunting'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(CandidateExperienceHunting::$rules);

        $candidateExperienceHunting = CandidateExperienceHunting::create($request->all());

return redirect()->route('candidate-hunt.show',array('candidate_hunt' => $candidateExperienceHunting->candidate_id))
            ->with('success', 'Experiência Profissional adicionada com sucesso');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $candidateExperienceHunting = CandidateExperienceHunting::find($id);

        return view('cms.hunting-admin.candidate-experience.show', compact('candidateExperienceHunting'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $candidateExperienceHunting = CandidateExperienceHunting::find($id);

        return view('cms.hunting-admin.candidate-experience.edit', compact('candidateExperienceHunting'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  CandidateExperienceHunting $candidateExperienceHunting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        request()->validate(CandidateExperienceHunting::$rules);

        $candidateExperienceHunting = CandidateExperienceHunting::find($id);
        $candidateExperienceHunting->update($request->all());

        return redirect()->route('candidate-hunt.show',array('candidate_hunt' => $request->input('candidate_id')))
            ->with('success', 'Experiência Profissional editada com sucesso');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
         $candidateExperienceHunting = candidateExperienceHunting::find($id);
        $id = $candidateExperienceHunting->candidate_id;
             $candidateExperienceHunting->delete();
        
        return redirect()->route('candidate-hunt.show',array('candidate_hunt' => $id))
            ->with('success', 'Experiência Profissional removidda com sucesso');
    }
}
