<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Content;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Storage;
use Image;

class AdController extends Controller
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
            
        $search_content_ad = $request->input("search_content_ad");

        $ads = Content::where('type', 2)
                                ->when($search_content_ad, function ($query, $search_content_ad) {
                                    
                                    $query->where(function($query) use($search_content_ad) {
                                        
                                        return $query->where("title", "like", "%{$search_content_ad}%")
                                                        ->orWhere("description", "like", "%{$search_content_ad}%");
                                    });
                                })
                                ->orderBy('id', 'desc')
                                ->paginate(10);

        $data = [
            'ads' => $ads,
            'search_content_ad' => $search_content_ad,
        ];

        return view('cms.ad.adList', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cms.ad.adForm');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $data['remove_imagem'] = $request->input('remove_imagem') == 1 ? 1 : 0;
        $data['type'] = 2;

        if( $request->remove_imagem){
            $data['image'] = '';
            $data['image_caption'] = '';
        }else if($request->hasFile('image') && $request->file('image')->isValid())
        {
            $request->file('image')->store('public/ads');
            $data['image'] =  $request->file('image')->hashName();
            $data['image_caption'] = $request->image_caption ? $request->image_caption : $request->image->getClientOriginalName();

            $path = storage_path("app/public/ads/{$data['image']}");

            if($data['category'] == 'banner'){
                Image::make($path)->fit(348, 520)->save(NULL, 85);
            }else{
                Image::make($path)->fit(600, 290)->save(NULL, 75);
            }
            
        }

        
        $this->validatorCreate($request);
        $ad = Content::create($data);

        return redirect('admin/anuncios')->with("success", "Anúncio $ad->title incluído com sucesso.");
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
        
        $ad = Content::where('id', $id)->first();
        
        if(!$ad || $ad->type != 2){
            return redirect()->back()->with("error", "Anúncio não cadastrado.");
        }
       
        $data = [
            'ad'    => $ad,
        ];

        return view('cms.ad.adForm', $data);
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
        $ad = Content::where('id', $id)->first();

        if(!$ad || $ad->type != 2){
            return redirect()->back()->with("error", "Anúncio não cadastrado.");
        }
       
        $data = $request->all();

        $data['remove_imagem'] = $request->input('remove_imagem') == 1 ? 1 : 0;
        
        if( $request->remove_imagem ){
            Storage::delete( 'public/ads/'.$ad->image );
            $data['image'] = '';
            $data['image_caption'] = '';
        }else if( $request->hasFile('image') && $request->file('image')->isValid() ){
            $request->file('image')->store('public/ads');
            $data['image'] =  $request->file('image')->hashName();
            $data['image_caption'] = $request->image_caption ? $request->image_caption : $request->image->getClientOriginalName();
            Storage::delete('public/ads/'.$ad->image);

            $path = storage_path("app/public/ads/{$data['image']}");

            if($data['category'] == 'banner'){
                Image::make($path)->fit(348, 520)->save(NULL, 85);
            }else{
                Image::make($path)->fit(600, 290)->save(NULL, 75);
            }
        }
        $this->validator($request);

        
        $ad->update( $data );
        
        return redirect("admin/anuncios/$ad->id/edit")->with("success", "Anúncio atualizado com sucesso.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ad = Content::where('id', $id)->first();

        if(!$ad || $ad->type != 2){
            return redirect()->back()->with("error", "Anúncio não cadastrado.");
        }
        
        Storage::delete('public/ads/'.$ad->image);
        $ad->delete();
        return redirect('admin/anuncios')->with("success", "O anúncio $ad->title foi deletado com sucesso.");
    }

    public function validator($request)
    {
        Validator::make($request->all(), [
            'image'                         => 'image|max:2048',
            'description'                   => 'required'
        ])->validate();
    }

    public function validatorCreate($request)
    {
        Validator::make($request->all(), [
            'image'                         => 'required|image|max:2048',
            'description'                   => 'required'
        ])->validate();
    }


}
