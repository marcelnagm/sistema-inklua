<?php

namespace App\Http\Controllers\Carteira;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\checkUserInkluer;
use Illuminate\Support\Facades\DB;
use App\Models\Content;

class ReportController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth:api');
        $this->middleware('checkPjTypeUser');
        $this->middleware('App\Http\Middleware\checkUserInkluer');
    }

    public function index(Request $request) {



        $data = array();
        if ($request->user()->admin == 1) {
            $vagas = $this->filters($request, \App\Models\Content::inkluaUsersContent());           
            
        } else {

            $office = $request->user()->office();
            $data['escritorio'] = $office->name;

            $vagas = $this->filters($request, $office->inkluaUsersContent());
           
        }
         $valo = clone $vagas;
            $valo->join('contents_client', 'content_id', '=', 'contents.id');
            $valo->join('client_condition', 'content_id', '=', 'contents.id');
            $valo = $valo->selectRaw('FORMAT(sum(((contents.salary * (client_condition.tax / 100)) * contents_client.vacancy) ),2) as total, contents.status,count(contents.status) as amount');
            $valo->groupby('status');
            if ($request->exists('debug5')) {
                dd(Controller::getEloquentSqlWithBindings($valo));
            }
            $data['carteira'] = $valo->get();
        $vagas = $vagas->get()->skip(10 * ($request->input('page') - 1))->take(10);
//        dd($vagas );
        
            $i = 0;

        foreach ($vagas as $content) {
//        dd($i);
            $data['vagas'][$i]['id'] = $content->id;
            $data['vagas'][$i]['status_front'] = $content->getStatusFront();            
            $data['vagas'][$i]['titulo_vagas'] = $content->title;
            $data['vagas'][$i]['criado_em']['value'] = $content->created_at->format('d/m/Y');
            $data['vagas'][$i]['criado_em']['ref'] =  \Carbon\Carbon::parse($content->created_at)->timestamp;
            $data['vagas'][$i]['salario'] =  $content->salary;            
//            $data['vagas'][$i]['salario'] = round(floatval($content->salary) ,2);            
            $contentclient = $content->contentclient();
            if ($contentclient != null) {
            $data['vagas'][$i]['posicoes'] = $contentclient->vacancy;
            $data['vagas'][$i]['taxa'] = $contentclient->clientcondition()->first()->tax;
            $data['vagas'][$i]['cliente'] = $contentclient->client()->first()->formal_name;
            
            }else{
            $data['vagas'][$i]['posicoes'] = 'Nao existe o dado';
            $data['vagas'][$i]['taxa'] = 'Nao existe o dado';
            $data['vagas'][$i]['cliente'] = 'Nao existe o dado';
                
            }

            $data['vagas'][$i]['recrutador'] = $content->user()->first()->fullname();
            if ($contentclient != null) {
            $data['vagas'][$i]['carteira'] = $data['vagas'][$i]['posicoes'] * ($data['vagas'][$i]['taxa'] / 100) * $data['vagas'][$i]['salario'];
            $data['vagas'][$i]['carteira'] =  round($data['vagas'][$i]['carteira'] ,2);
            }else{
            $data['vagas'][$i]['carteira'] = 'Nao existe o dado';              
            }
            $i++;
        }
//        dd($data);
        return $data;
    }

    public function filters(Request $request, $vagas) {



        if ($request->exists('content_id')) {
            $vagas = $vagas->where('contents.id', '=', $request->input('content_id'));
        } else {
            if ($request->exists('date_start')) {
                $vagas = $vagas->where('contents.created_at', '>=', $request->input('date_start'));
            }
            if ($request->exists('date_end')) {
                $vagas = $vagas->where('contents.created_at', '<=', $request->input('date_end'));
            }
            if ($request->exists('title')) {
                $vagas = $vagas->where('contents.title', 'like', '%' . $request->input('title') . '%');
            }
            if ($request->exists('client')) {
                $vagas = $vagas->whereRaw('contents.id in (select content_id as id from contents_client,clients where contents_client.client_id = clients.id and clients.formal_name like ? )', '%' . $request->input('client') . '%');
            }
            if ($request->exists('recruiter')) {
                $vagas = $vagas->whereRaw('contents.user_id in (select id as id from users where users.name like ?  or users.lastname like ?)', array('%' . $request->input('client') . '%', '%' . $request->input('client') . '%'));
            }
            if ($request->exists('office')) {
                $vagas = $vagas->whereRaw('contents.user_id in (select user_id as id from inklua_users where office_id = ?)', array($request->input('office')));
            }
        }
        if ($request->exists('debug')) {
            dd(Controller::getEloquentSqlWithBindings($vagas));
        }



        return $vagas;
    }

}
