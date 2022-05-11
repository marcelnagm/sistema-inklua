<?php

namespace App\Http\Controllers\MapeamentoTech;

use App\Models\CandidateStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 

/**
 * Class CandidateStatusController
 * @package App\Http\Controllers
 */
class CandidateStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $candidateStatuses = CandidateStatus::paginate();

        return view('cms.mapeamento-tech.candidate-status.index', compact('candidateStatuses'))
            ->with('i', (request()->input('page', 1) - 1) * $candidateStatuses->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $candidateStatus = new CandidateStatus();
        return view('cms.mapeamento-tech.candidate-status.create', compact('candidateStatus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(CandidateStatus::$rules);

        $candidateStatus = CandidateStatus::create($request->all());

        return redirect()->route('status.index')
            ->with('success', 'CandidateStatus created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $candidateStatus = CandidateStatus::find($id);

        return view('cms.mapeamento-tech.candidate-status.show', compact('candidateStatus'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $candidateStatus = CandidateStatus::find($id);

        return view('cms.mapeamento-tech.candidate-status.edit', compact('candidateStatus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  CandidateStatus $candidateStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        request()->validate(CandidateStatus::$rules);
        $candidateStatus = CandidateStatus::find($id);
        $candidateStatus->update($request->all());

        return redirect()->route('status.index')
            ->with('success', 'CandidateStatus updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $candidateStatus = CandidateStatus::find($id)->delete();

        return redirect()->route('status.index')
            ->with('success', 'CandidateStatus deleted successfully');
    }
}
