<?php

namespace App\Http\Controllers\Carteira;

use App\Models\OfficeRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class OfficeRoleController
 * @package App\Http\Controllers
 */
class OfficeRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $officeRoles = OfficeRole::paginate();

        return view('cms.carteira.office-role.index', compact('officeRoles'))
            ->with('i', (request()->input('page', 1) - 1) * $officeRoles->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $officeRole = new OfficeRole();
        return view('cms.carteira.office-role.create', compact('officeRole'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(OfficeRole::$rules);

        $officeRole = OfficeRole::create($request->all());

        return redirect()->route('office_role.index')
            ->with('success', 'OfficeRole created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $officeRole = OfficeRole::find($id);

        return view('cms.carteira.office-role.show', compact('officeRole'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $officeRole = OfficeRole::find($id);

        return view('cms.carteira.office-role.edit', compact('officeRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  OfficeRole $officeRole
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OfficeRole $officeRole)
    {
        request()->validate(OfficeRole::$rules);

        $officeRole->update($request->all());

        return redirect()->route('office_role.index')
            ->with('success', 'OfficeRole updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $officeRole = OfficeRole::find($id)->delete();

        return redirect()->route('office_role.index')
            ->with('success', 'OfficeRole deleted successfully');
    }
}
