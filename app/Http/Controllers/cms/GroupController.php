<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Content;
use App\Models\Group;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkAdminPermission');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $logged = Auth::user();
            
        $search_group = $request->input("search_group");

        $groups = Group::when($search_group, function ($query, $search_group) {
                                    
                                    $query->where(function($query) use($search_group) {
                                        
                                        return $query->where("title", "like", "%{$search_group}%");
                                    });
                                })
                                ->orderBy('id', 'desc')
                                ->paginate(10);

        $data = [
            'groups' => $groups,
            'search_group' => $search_group,
        ];

        return view('cms.group.groupList', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'positionItens' => Content::where('type', 1)->orderBy('title', 'asc')->get(),
        ];
        return view('cms.group.groupForm', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except(['ordenation']);

        $this->validator($data);
        
        $group = Group::create($data);



        Content::where('group_id', $group->id)->update(['group_id' => NULL]);

        Content::whereIn('id', $request['positionItens'])->update(['group_id' => $group->id]);

        // Atualiza o ordenation em todos os positions do mesmo grupo
        if($request['ordenation'] != ''){
                Content::where('group_id', $group->id)->update(['ordenation' => $request['ordenation'] ]);
        }

        return redirect('admin/grupo/vagas')->with("success", "Grupo $group->title incluído com sucesso.");
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $group = Group::where('id', $id)->first();

        if(!$group){
            return redirect()->back()->with("error", "Grupo não cadastrado.");
        }
        $position = Content::where('group_id', $group->id)->first();

        $data = [
            'logged' => Auth::user(),
            'group' => $group,
            'relatedPositions' => $group->contents()->pluck('group_id')->all(),
            'positionItens' => Content::where('type', 1)->orderBy('title', 'ASC')->get(),
            'position' => $position,
        ]; 

        // dd($data);
        return view('cms.group.groupForm', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $group = Group::where('id', $id)->first();

        if(!$group){
            return redirect()->back()->with("error", "Grupo não cadastrado.");
        }

        $data = $request->except(['ordenation']);

        // dd($data);

        $this->validator($data);
        
        $group->update($data);

        Content::where('group_id', $group->id)->update(['group_id' => NULL]);

        Content::whereIn('id', $request['positionItens'])->update(['group_id' => $group->id]);

         // Atualiza o ordenation em todos os positions do mesmo grupo
         if($request['ordenation'] != ''){
            Content::where('group_id', $group->id)->update(['ordenation' => $request['ordenation'] ]);
    }
        
        return redirect("admin/grupo/vagas/$group->id/edit")->with("success", "Grupo atualizado com sucesso.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $group = Group::where('id', $id)->first();

        if(!$group){
            return redirect()->back()->with("error", "Grupo não cadastrado.");
        }

        Content::where('group_id', $group->id)->update(['group_id' => NULL]);

        $group->delete();

        return redirect('admin/grupo/vagas')->with("success", "O Grupo $group->title foi deletado com sucesso.");
    }

    public function validator($data)
    {
        Validator::make($data, [
            'title' => ['required', 'string', 'max:255'],
            'positionItens' => ['required'],
        ])->validate();
    }
}
