<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Content;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Storage;
use Image;

class ArticleController extends Controller
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
            
        $search_article = $request->input("search_article");

        $articles = Content::where('type', 3)
                                ->when($search_article, function ($query, $search_article) {
                                    
                                    $query->where(function($query) use($search_article) {
                                        
                                        return $query->where("title", "like", "%{$search_article}%")
                                                        ->orWhere("description", "like", "%{$search_article}%");
                                    });
                                })
                                ->orderBy('id', 'desc')
                                ->paginate(10);

        $data = [
            'articles' => $articles,
            'search_article' => $search_article,
        ];

        return view('cms.article.article_list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cms.article.article_form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Content $content)
    {
        $data = $request->all();

        $data['remove_imagem'] = $request->input('remove_imagem') == 1 ? 1 : 0;
        $data['type'] = 3;

        if( $request->remove_imagem){
            $data['image'] = '';
            $data['image_caption'] = '';
        }else if($request->hasFile('image') && $request->file('image')->isValid()){
            $request->file('image')->store('public/articles');
            $data['image'] =  $request->file('image')->hashName();
            $data['image_caption'] = $request->image_caption ? $request->image_caption : $request->image->getClientOriginalName();

            $path = storage_path("app/public/articles/{$data['image']}");
            Image::make($path)->fit(600, 290)->save(NULL, 75);
        }

        $this->validator($request);   

        $article = Content::create($data);

        return redirect('admin/artigos')->with("success", "Conteúdo externo '{$article->title}' incluído com sucesso.");
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
        
        $article = Content::where('id', $id)->first();
        

        if(!$article || $article->type != 3){
            return redirect()->back()->with("error", "Conteúdo externo não cadastrado.");
        }

        $data = [
            'article'    => $article,
        ];

        return view('cms.article.article_form', $data);
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
        $article = Content::where('id', $id)->first();

        if(!$article || $article->type != 3){
            return redirect()->back()->with("error", "Conteúdo externo não cadastrado.");
        }
       
        $data = $request->all();

        $data['remove_imagem'] = $request->input('remove_imagem') == 1 ? 1 : 0;
        
        if( $request->remove_imagem ){ 
            Storage::delete( 'public/articles/'.$article->image );
            $data['image'] = '';
            $data['image_caption'] = '';
        }else if( $request->hasFile('image') && $request->file('image')->isValid() ){
            $request->file('image')->store('public/articles');
            $data['image'] =  $request->file('image')->hashName();
            $data['image_caption'] = $request->image_caption ? $request->image_caption : $request->image->getClientOriginalName();
            Storage::delete('public/articles/'.$article->image);

            $path = storage_path("app/public/articles/{$data['image']}");
            Image::make($path)->fit(600, 290)->save(NULL, 75);
        }

        $this->validator($request);


        
        $article->update( $data );
        
        return redirect("admin/artigos/$article->id/edit")->with("success", "Conteúdo externo atualizado com sucesso.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Content::where('id', $id)->first();

        if(!$article || $article->type != 3){
            return redirect()->back()->with("error", "Conteúdo externo não cadastrado.");
        }
        
        Storage::delete('public/articles/'.$article->image);
        $article->delete();
        return redirect('admin/artigos')->with("success", "O conteúdo externo '{$article->title}' foi deletado com sucesso.");
    }

    public function validator($request)
    {
        Validator::make($request->all(), [
            'image'                         => 'required|image|max:2048',
            'description'                   => 'required'
        ])->validate();
    }
}
