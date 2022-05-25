<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class MyContentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('checkPjTypeUser');
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $search = $request->input("q");
        $status = $request->input("status");

        $myContents = $user->getMyContents($search, $status);

        return response()->json([
            'myContents' => $myContents,
        ]);
    }

    public function store(Request $request) {
        $user = $request->user();
        
        $data = $request->only([
            'title',
            'salary',
            'contract_type',
            'image',
            'state',
            'city',
            'description',
            'application',
        ]);

        $data['user_id'] = $user->id;
        $data['status'] = 'aguardando_aprovacao';
        $data['type'] = 1;

        $validator = $this->validator($data);
        if($validator->fails()){
            return response()->json(
                [
                    "errors" => $validator->messages()
                ], 400, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        } 

        $newContent = Content::create($data);
        $newContent['application_type'] = $newContent->getApplicationType();

        return response()->json($newContent);
    }

    public function edit($id) {
        
        $content = Content::where('id', $id)->first();        

        if(!$content){
            return response()->json([
                'error' => 'Vaga não cadastrada',
            ], 400);
        }

        if($content->status != "reprovada"){
            return response()->json([
                'error' => 'Vaga nao pode ser editada',
            ], 400);
        }

        return response()->json($content);
    }

    public function update(Request $request, $id) {
        
        $user = $request->user();
        $content = Content::where('id', $id)->first();        

        if(!$content){
            return response()->json([
                'error' => 'Vaga não cadastrada',
            ], 400);
        }

        if($content->status != "reprovada"){
            return response()->json([
                'error' => 'Vaga nao pode ser editada',
            ], 400);
        }
        
        $data = $request->only([
            'title',
            'salary',
            'image',
            'contract_type',
            'state',
            'city',
            'description',
            'application',
        ]);

        $data['user_id'] = $user->id;
        $data['status'] = 'aguardando_aprovacao';
        $data['type'] = 1;

        $content->update($data);
        $content['application_type'] = $content->getApplicationType();
        return response()->json($content);
    }

    public function myContentStatus(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'existContentForCNPJ' => $user->checkExistenceOfPositionByCnpj() ,
            'contentsFromUser' => $user->contents()->count()
        ]);
    }


    public function validator($data)
    {
        $response = Validator::make($data, [
            'image' => ['starts_with:https://inklua.com.br/']
        ]);

        return $response;
    }
}
