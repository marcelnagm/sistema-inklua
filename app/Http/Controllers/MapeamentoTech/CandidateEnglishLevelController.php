<?php

namespace App\Http\Controllers\MapeamentoTech;

use App\Models\CandidateEnglishLevel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 

/**
 * Class CandidateEnglishLevelController
 * @package App\Http\Controllers
 */
class CandidateEnglishLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $candidateEnglishLevels = CandidateEnglishLevel::paginate();

        return view('cms.candidate-english-level.index', compact('candidateEnglishLevels'))
            ->with('i', (request()->input('page', 1) - 1) * $candidateEnglishLevels->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $candidateEnglishLevel = new CandidateEnglishLevel();
        return view('cms.candidate-english-level.create', compact('candidateEnglishLevel'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(CandidateEnglishLevel::$rules);

        $candidateEnglishLevel = CandidateEnglishLevel::create($request->all());

        return redirect()->route('english_level.index')
            ->with('success', 'CandidateEnglishLevel created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $candidateEnglishLevel = CandidateEnglishLevel::find($id);

        return view('cms.candidate-english-level.show', compact('candidateEnglishLevel'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $candidateEnglishLevel = CandidateEnglishLevel::find($id);

        return view('cms.candidate-english-level.edit', compact('candidateEnglishLevel'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  CandidateEnglishLevel $candidateEnglishLevel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        request()->validate(CandidateEnglishLevel::$rules);
        $candidateEnglishLevel = CandidateEnglishLevel::find($id);
        $candidateEnglishLevel->update($request->all());
        $candidateEnglishLevel->update($request->all());

        return redirect()->route('english_level.index')
            ->with('success', 'CandidateEnglishLevel updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $candidateEnglishLevel = CandidateEnglishLevel::find($id)->delete();

        return redirect()->route('english_level.index')
            ->with('success', 'CandidateEnglishLevel deleted successfully');
    }
}
