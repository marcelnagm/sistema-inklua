<?php

namespace App\Http\Controllers\Carteira;

use App\Models\ClientCondition;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Condition;

/**
 * Class ClientConditionController
 * @package App\Http\Controllers
 */
class ClientConditionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientConditions = ClientCondition::paginate();

        return view('cms.carteira.client-condition.index', compact('clientConditions'))
            ->with('i', (request()->input('page', 1) - 1) * $clientConditions->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {   
        $condtions = Condition::all();
        
        $clientCondition = new ClientCondition(array('client_id' => $id));
        return view('cms.carteira.client-condition.create', compact('clientCondition','condtions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(ClientCondition::$rules);

        $clientCondition = ClientCondition::create($request->all());

        return redirect('/clients/'.$clientCondition->client_id)
            ->with('success', 'ClientCondition created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $clientCondition = ClientCondition::find($id);

        return view('cms.carteira.client-condition.show', compact('clientCondition'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $clientCondition = ClientCondition::find($id);
 $condtions = Condition::all();
        return view('cms.carteira.client-condition.edit', compact('clientCondition','condtions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  ClientCondition $clientCondition
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClientCondition $clientCondition)
    {
        request()->validate(ClientCondition::$rules);

        $clientCondition->update($request->all());

      return redirect('/clients/'.$clientCondition->client_id)
            ->with('success', 'ClientCondition created successfully.');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $clientCondition = ClientCondition::find($id);
        $clientCondition->active = 0;
        $clientCondition->save();
       return redirect('/clients/'.$clientCondition->client_id)
            ->with('success', 'ClientCondition created successfully.');
    }
}
