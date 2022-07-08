<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\ContentClient;
use App\Models\ContentCancel;

class MyContentController extends Controller
{

    static $visible = [
        'title',
        'salary',
        'contract_type',
        'image',
        'state',
        'city',
        'description',
        'application',
        'application_type',
        'district',
        'benefits',
        'requirements',
        'hours',
        'english_level',
        'observation',
    ];

    public function __construct() {
        $this->middleware('auth:api');
        $this->middleware('checkPjTypeUser');
    }

    public function index(Request $request) {
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

        $data = $request->only(MyContentController::$visible);

        $data['user_id'] = $user->id;
        $data['status'] = 'aguardando_aprovacao';
        $data['type'] = 1;

        $validator = $this->validator($data);
        if ($validator->fails()) {
            return response()->json(
                            [
                                "errors" => $validator->messages()
                            ], 400, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                            JSON_UNESCAPED_UNICODE
            );
        }

        $newContent = Content::create($data);
        $newContent['application_type'] = $newContent->getApplicationType();

        if ($user->isInklua()) {

            $data = $request->only(array_keys(ContentClient::$rules));
            $data['content_id'] = $newContent->id;
            $data['user_id'] = $user->id;
//            dd($data);

            ContentClient::create($data);

        }



        return response()->json($newContent);
    }

    public function edit($id) {

        $content = Content::where('id', $id)->first();

        if (!$content) {
            return response()->json([
                        'error' => 'Vaga não cadastrada',
                            ], 400);
        }

        if ($content->status != "reprovada") {
            return response()->json([
                        'error' => 'Vaga nao pode ser editada',
                            ], 400);
        }

        return response()->json($content);
    }

    public function update(Request $request, $id) {

        $user = $request->user();
        $content = Content::where('id', $id)->first();

        if (!$content) {
            return response()->json([
                        'error' => 'Vaga não cadastrada',
                            ], 400);
        }

        if ($content->status != "reprovada") {
            return response()->json([
                        'error' => 'Vaga nao pode ser editada',
                            ], 400);
        }

        $data = $request->only(MyContentController::$visible);

        $data['user_id'] = $user->id;
        $data['status'] = 'aguardando_aprovacao';
        $data['type'] = 1;

        $content->update($data);
        $content['application_type'] = $content->getApplicationType();
        return response()->json($content);
    }

    public function myContentStatus(Request $request) {
        $user = $request->user();

        return response()->json([
                    'existContentForCNPJ' => $user->checkExistenceOfPositionByCnpj(),
                    'contentsFromUser' => $user->contents()->count()
        ]);
    }

    public function validator($data) {
        $response = Validator::make($data, [
                    'image' => ['starts_with:https://inklua.com.br/']
        ]);

        return $response;
    }

    
public function repos($id){
      
       $content = Content::where('id', $id)->first();
       $content->status='reposicao';
       $content->save();                      
         return response()->json([
                    'message' => 'Vaga Reposta',
                    'content_id' => $content->id
        ]);
}

public function approve($id){
      
       $content = Content::where('id', $id)->first();
       $content->status='publicada';
       $content->save();        
         return response()->json([
                    'message' => 'Vaga Aprovada',
                    'content_id' => $content->id
        ]);
}


public function close($id){
       $content = Content::where('id', $id)->first();
       $content->status='fechada';
       $content->save();        
         return response()->json([
                    'message' => 'Vaga Fechada',
                    'content_id' => $content->id
        ]);
}
public function details($id){
       $content = Content::where('id', $id)->first();
       
         return response()->json([
                    'content' => [$content->id, $content->observation]
        ]);
}

public function cancel(Request $request, $id){
       $content = Content::where('id', $id)->first();
       $content->status='cancelada';
       $content->save();    
       
       $cc = $content->contentclient()->first();
//       dd($cc);
       
       $cancel = new ContentCancel();
       $cancel->content_id= $cc->content_id;
       $cancel->client_id= $cc->client_id;
       $cancel->user_id=  $request->user()->id;
       $cancel->reason= $request->input('reason');
       $cancel->save();
       
         return response()->json([
                    'message' => 'Vaga Cancelada',
                    'content_id' => $content->id
        ]);
}
    
}
