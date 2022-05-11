<?php

namespace App\Http\Controllers\MapeamentoTech;

use App\Models\State;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 

/**
 * Class StateController
 * @package App\Http\Controllers
 */
class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $states = State::paginate();

        return view('cms.mapeamento-tech.state.index', compact('states'))
            ->with('i', (request()->input('page', 1) - 1) * $states->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $state = new State();
        return view('state.create', compact('state'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(State::$rules);

        $state = State::create($request->all());

        return redirect()->route('states.index')
            ->with('success', 'State created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $state = State::find($id);

        return view('state.show', compact('state'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $state = State::find($id);

        return view('state.edit', compact('state'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  State $state
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, State $state)
    {
        request()->validate(State::$rules);

        $state->update($request->all());

        return redirect()->route('states.index')
            ->with('success', 'State updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $state = State::find($id)->delete();

        return redirect()->route('states.index')
            ->with('success', 'State deleted successfully');
    }
}
