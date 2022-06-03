<?php

namespace App\Http\Controllers\Carteira;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Validation\Rule;

/**
 * Class ClientController
 * @package App\Http\Controllers
 */
class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::paginate();

        return view('cms.carteira.client.index', compact('clients'))
            ->with('i', (request()->input('page', 1) - 1) * $clients->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $states = State::all();
        $client = new Client();
        return view('cms.carteira.client.create', compact('client','states'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        
        request()->validate(Client::$rules);

        $client = Client::create($request->all());

        return redirect()->route('clients.index')
            ->with('success', 'Novo CLiente Criado.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Client::find($id);

        return view('cms.carteira.client.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $states = State::all();
        $client = Client::find($id);

        return view('cms.carteira.client.edit', compact('client','states'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Client $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        $rules = Client::$rules;
        unset ($rules[ 'cnpj'] );
        request()->validate($rules);

        $client->update($request->all());

        return redirect()->route('clients.index')
            ->with('success', 'CLiente editado com sucesso');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $client = Client::find($id)->delete();

        return redirect()->route('clients.index')
            ->with('success', 'CLiente removido com sucesso');
    }
}
