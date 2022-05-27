<?php

namespace App\Http\Controllers\Carteira;

use App\Models\InkluaOffice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


/**
 * Class InkluaOfficeController
 * @package App\Http\Controllers
 */
class InkluaOfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inkluaOffices = InkluaOffice::paginate();

        return view('cms.carteira.inklua-office.index', compact('inkluaOffices'))
            ->with('i', (request()->input('page', 1) - 1) * $inkluaOffices->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $inkluaOffice = new InkluaOffice();
        return view('cms.carteira.inklua-office.create', compact('inkluaOffice'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(InkluaOffice::$rules);

        $inkluaOffice = InkluaOffice::create($request->all());

        return redirect()->route('inklua_office.index')
            ->with('success', 'InkluaOffice created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $inkluaOffice = InkluaOffice::find($id);

        return view('cms.carteira.inklua-office.show', compact('inkluaOffice'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $inkluaOffice = InkluaOffice::find($id);

        return view('cms.carteira.inklua-office.edit', compact('inkluaOffice'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  InkluaOffice $inkluaOffice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InkluaOffice $inkluaOffice)
    {
        request()->validate(InkluaOffice::$rules);

        $inkluaOffice->update($request->all());

        return redirect()->route('inklua_office.index')
            ->with('success', 'InkluaOffice updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $inkluaOffice = InkluaOffice::find($id)->delete();

        return redirect()->route('inklua_office.index')
            ->with('success', 'InkluaOffice deleted successfully');
    }
}
