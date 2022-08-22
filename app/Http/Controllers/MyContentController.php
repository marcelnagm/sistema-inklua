<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\ContentClient;
use App\Models\ContentCancel;

class MyContentController extends Controller {

  

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

        $data = $request->only(Content::$sendable);

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
        $newContent['salary'] = round($newContent['salary'], 2);
        if ($user->isInklua()) {


            $data = $request->only(array_keys(ContentClient::$rules));
            $response = $this->validate_request($data, ContentClient::$rules);
            if ($response instanceof \Illuminate\Http\JsonResponse)
                return $response;
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
        if ($user->isInklua()) {

            $contentclient = ContentClient::where('content_id', $id)->first();
            $data = $request->only(array_keys(ContentClient::$rules));
            $response = $this->validate_request($data, ContentClient::$rules);
            if ($response instanceof \Illuminate\Http\JsonResponse)
                return $response;

            $data['content_id'] = $id;
            $data['user_id'] = $user->id;
//            dd($data);

            $contentclient->update($data);
        }
        $data = $request->only(Content::$sendable);

        $data['user_id'] = $user->id;
        $data['status'] = 'aguardando_aprovacao';
        $data['type'] = 1;

        $content->update($data);
        $content['application_type'] = $content->getApplicationType();
        $content['salary'] = round($content['salary'], 2);
        return response()->json($content);
    }

    public function myContentStatus(Request $request) {
        $user = $request->user();

        return response()->json([
                    'existContentForCNPJ' => $user->checkExistenceOfPositionByCnpj(),
                    'contentsFromUser' => $user->contents()->count(),
                    'firstContent' => $user->first_position()
        ]);
    }

    public function validator($data) {
        $response = Validator::make($data, [
                    'image' => ['starts_with:'.env('APP_URL_IMAGES')]
        ]);

        return $response;
    }

    public function clientevaluate($id) {

        $content = Content::findOrFail($id);
        $content->status = 'reposicao';
        $content->save();
        return response()->json([
                    'status' => true,
                    'error' => false,
                    'msg' => 'Vaga Reposta'
        ]);
    }

    public function approve($id) {

        $content = Content::findOrFail($id);
        $content->status = 'publicada';
        $content->save();
        return response()->json([
                    'status' => true,
                    'error' => false,
                    'msg' => 'Vaga Aprovada',
        ]);
    }

    public function close($id) {
        $content = Content::findOrFail($id);
        $content->status = 'fechada';
        $content->save();
        return response()->json([
                    'status' => true,
                    'error' => false,
                    'msg' => 'Vaga Fechada'
        ]);
    }

    public function show($id) {
        $content = Content::where('id', $id)->first();

        return response()->json([
                    'data' =>
            ['content' => $content->toArray()]
        ]);
    }
    
    public function details($id) {
        $content = Content::where('id', $id)->first();

        return response()->json([
                    'content' => [$content->id, $content->observation]
        ]);
    }

    public function cancel(Request $request, $id) {
        $content = Content::findOrFail($id);

        if (!$content->hasPermission($request))
            return array('status' => false, 'error' => true, 'msg' => 'Você não tem permissão para essa operação');

        $content->status = 'cancelada';
        $content->save();

        $cc = $content->contentclient();
//       dd($cc);
        if ($cc == null)
            return response()->json([
                        'status' => false,
                        'error' => true,
                        'msg' => 'Associação com cliente não criada, falta a informação da vaga de que cliente esta associado, posição e condições do cliente!',
            ]);
        $cc = $cc->first();
        if (!$request->exists('reason'))
            return response()->json([
                        'status' => false,
                        'error' => true,
                        'msg' => 'Necessário o envio do motivo de cancelamento',
            ]);

        $cancel = new ContentCancel();
        $cancel->content_id = $cc->content_id;
        $cancel->client_id = $cc->client_id;
        $cancel->user_id = $request->user()->id;
        $cancel->reason = $request->input('reason');
        $cancel->save();

        return response()->json([
                    'status' => true,
                    'error' => false,
                    'msg' => 'Vaga Cancelada'
        ]);
    }

}
