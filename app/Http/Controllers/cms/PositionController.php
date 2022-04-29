<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Content;
use App\Models\Group;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Jobs\Importer;
use Storage;

class PositionController extends Controller
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
            
        $search_position = $request->input("search_position");

        $positions = Content::where('type', 1)
                                ->when($search_position, function ($query, $search_position) {
                                    
                                    $query->where(function($query) use($search_position) {
                                        
                                        return $query->where("title", "like", "%{$search_position}%")
                                                        ->orWhere("description", "like", "%{$search_position}%");
                                    });
                                })
                                ->orderBy('ordenation', 'desc')
                                ->orderBy('id', 'desc')
                                ->paginate(10);

        $data = [
            'positions' => $positions,
            'search_position' => $search_position,
        ];

        return view('cms.position.positionList', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $logged = Auth::user();
        
        $position = Content::where('id', $id)->first();
        $groups = Group::get();
        

        if(!$position || $position->type != 1){
            return redirect()->back()->with("error", "Vaga não encontrada.");
        }

        $data = [
            'position'    => $position,
            'groups' => $groups,
        ];

        return view('cms.position.positionForm', $data);
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
        $position = Content::where('id', $id)->first();

        if(!$position || $position->type != 1){
            return redirect()->back()->with("error", "vaga não encontrada.");
        }
       
        $data = $request->only([
            'image',
            'ordenation',
            'group_id',
        ]);
        
        if( $request->input('remove_imagem') ){
            Storage::delete( 'public/positions/'.$position->image );
            $data['image'] = '';
            $data['image_caption'] = '';
        }else if( $request->hasFile('image') && $request->file('image')->isValid() ){
            $request->file('image')->store('public/positions');
            $data['image'] =  $request->file('image')->hashName();
            $data['image_caption'] = $request->image_caption ? $request->image_caption : $request->image->getClientOriginalName();
            Storage::delete('public/positions/'.$position->image);

            $path = storage_path("app/public/positions/{$data['image']}");
            // Image::make($path)->fit(600, 290)->save(NULL, 75);
        }
        
        $this->validator($request);

        $position->update( $data );

        // Atualiza o ordenation em todos os positions do mesmo grupo
        if($position->group_id){
            if($data['ordenation'] != ''){
                Content::where('group_id', $position->group_id)->update(['ordenation' => $data['ordenation'] ]);
            }
        }
        
        if($request['nextUrl']){
            return redirect($request['nextUrl'])->with("success", "Vaga atualizada com sucesso.");
        }

        return redirect("admin/vagas/$position->id/edit")->with("success", "Vaga atualizada com sucesso.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function validator($request)
    {
        Validator::make($request->all(), [
            'image'                         => 'image|max:2048'
        ])->validate();
    }

    public function importPositions(){
        echo "Importação";
        Importer::dispatch();
        return;
    }
}
