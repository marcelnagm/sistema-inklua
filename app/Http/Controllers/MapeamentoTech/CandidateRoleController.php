<?php

namespace App\Http\Controllers\MapeamentoTech;

use App\Models\CandidateRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 

/**
 * Class CandidateRoleController
 * @package App\Http\Controllers
 */
class CandidateRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $candidateRoles = CandidateRole::paginate();

        return view('cms.candidate-role.index', compact('candidateRoles'))
            ->with('i', (request()->input('page', 1) - 1) * $candidateRoles->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $candidateRole = new CandidateRole();
        return view('cms.candidate-role.create', compact('candidateRole'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(CandidateRole::$rules);

        $candidateRole = CandidateRole::create($request->all());

        return redirect()->route('role.index')
            ->with('success', 'CandidateRole created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $candidateRole = CandidateRole::find($id);

        return view('cms.candidate-role.show', compact('candidateRole'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $candidateRole = CandidateRole::find($id);

        return view('cms.candidate-role.edit', compact('candidateRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  CandidateRole $candidateRole
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        request()->validate(CandidateRole::$rules);
        $candidateRole = CandidateRole::find($id);
        
        $candidateRole->update($request->all());
//        dd($request->all());
        return redirect()->route('role.index')
            ->with('success', 'CandidateRole updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $candidateRole = CandidateRole::find($id)->delete();

        return redirect()->route('role.index')
            ->with('success', 'CandidateRole deleted successfully');
    }
}
