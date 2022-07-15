<?php

namespace App\Http\Controllers\Lider;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\checkUserInkluer;
use Illuminate\Support\Facades\DB;
use App\Models\Content;
use Carbon;

class ReportController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth:api');
        $this->middleware('checkPjTypeUser');
        $this->middleware('App\Http\Middleware\checkUserInkluer');
    }

    public function index_repos(Request $request) {



        $data = array();

        if ($request->user()->admin == 1) {
            $vagas = $this->filters($request, \App\Models\Content::inkluaUsersContent());
        } else {

            $office = $request->user()->office();
            $data['escritorio'] = $office->name;

            $vagas = $this->filters($request, Content::where('office_id',$office->id));
            $vagas = $vagas->where('type',1);
            $vagas = $vagas->where('status','reposicao');
            if ($request->exists('debug2')) {
                dd(Controller::getEloquentSqlWithBindings($vagas));
            }
        }
        $valo = clone $vagas;
        $valo->join('contents_client', 'content_id', '=', 'contents.id');
        $valo->join('client_condition', 'content_id', '=', 'contents.id');
        $valo->whereRaw('contents_client.content_id = contents.id');
        $valo->whereRaw('client_condition.id = contents_client.client_condition_id');
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
            $contentclient = $content->contentclient();
            $data['vagas'][$i]['id'] = $content->id;
            $data['vagas'][$i]['titulo_vagas'] = $content->title;
            if ($contentclient != null) {
                $data['vagas'][$i]['vagas'] = $contentclient->replaced;                
            } else {
                $data['vagas'][$i]['vagas'] = '-';
            }

            $data['vagas'][$i]['reabertura']['value'] = $content->updated_at->format('d/m/Y');
            $data['vagas'][$i]['reabertura']['ref'] = \Carbon\Carbon::parse($content->created_at)->timestamp;
            $data['vagas'][$i]['recrutador'] = $content->user()->first()->fullname();
            $data['vagas'][$i]['entrega']['value'] = $content->updated_at->addDays(5)->format('d/m/Y');
            $data['vagas'][$i]['entrega']['ref'] = \Carbon\Carbon::parse($content->updated_at->addDays(5))->timestamp;
            if ($contentclient != null) {
                $data['vagas'][$i]['cliente'] = $contentclient->client()->first()->formal_name;
            } else {
                $data['vagas'][$i]['cliente'] = '-';
            }
            $data['vagas'][$i]['overview']['value'] = $content->updated_at->addDays(3)->format('d/m/Y');
            $data['vagas'][$i]['overview']['ref'] = \Carbon\Carbon::parse($content->updated_at->addDays(3))->timestamp;
            if ($contentclient != null) {
                $data['vagas'][$i]['taxa'] = $contentclient->clientcondition()->first()->tax;
                $data['vagas'][$i]['reposicao'] = $data['vagas'][$i]['vagas'] * $content->salary;
                $data['vagas'][$i]['reposicao'] = 'R$' . number_format(floatval($data['vagas'][$i]['reposicao']), 2, '.', '.');
            } else {
                $data['vagas'][$i]['reposicao'] = '-';
            }
            $data['vagas'][$i]['c/o'] = $content->getLikesCount() . '/' . $data['vagas'][$i]['vagas'];

            $i++;
        }
//        dd($data);
        return $data;
    }

    public function index_cliente(Request $request) {



        $data = array();

        if ($request->user()->admin == 1) {
            $vagas = $this->filters($request, Content::whereRaw('contents.id in (select job_id from candidate_report where report_status_id = 8)'));
        } else {

            $office = $request->user()->office();
            $data['escritorio'] = $office->name;

            $vagas = $this->filters($request, Content::where('office_id',$office->id));
            $vagas = $vagas->whereIn('status',array('publicada','reposicao'));
            $vagas = $vagas->whereRaw('contents.id in (select job_id from candidate_report where report_status_id =8)');
        }
//        dd('va');
        $vagas = $vagas->whereRaw('contents.id in (select job_id from candidate_report where report_status_id = 8)');
        $valo = clone $vagas;
        $valo->join('contents_client', 'content_id', '=', 'contents.id');
        $valo->join('client_condition', 'content_id', '=', 'contents.id');
        $valo = $valo->selectRaw('FORMAT(sum(((contents.salary * (client_condition.tax / 100)) * contents_client.vacancy) ),2) as total, contents.status,count(contents.status) as amount');
        $valo->groupby('status');
        if ($request->exists('debug5')) {
            dd(Controller::getEloquentSqlWithBindings($valo));
        }
        if ($request->exists('debug3')) {
            dd(Controller::getEloquentSqlWithBindings($vagas));
        }
        $data['carteira'] = $valo->get();
        $vagas = $vagas->get()->skip(10 * ($request->input('page') - 1))->take(10);
//        dd($vagas );

        $i = 0;

        foreach ($vagas as $content) {
//        dd($i);
            $contentclient = $content->contentclient();
            $data['vagas'][$i]['id'] = $content->id;
            $data['vagas'][$i]['titulo_vagas'] = $content->title;
            if ($contentclient != null) {
                $data['vagas'][$i]['vagas'] = $contentclient->vacancy;
            } else {
                $data['vagas'][$i]['vagas'] = '-';
            }

            $data['vagas'][$i]['criado_em']['value'] = $content->created_at->format('d/m/Y');
            $data['vagas'][$i]['criado_em']['ref'] = \Carbon\Carbon::parse($content->created_at)->timestamp;
            $data['vagas'][$i]['recrutador'] = $content->user()->first()->fullname();
            $data['vagas'][$i]['entrega']['value'] = $content->created_at->addDays(5)->format('d/m/Y');
            $data['vagas'][$i]['entrega']['ref'] = \Carbon\Carbon::parse($content->created_at->addDays(5))->timestamp;
            if ($contentclient != null) {
                $data['vagas'][$i]['cliente'] = $contentclient->client()->first()->formal_name;
            } else {
                $data['vagas'][$i]['cliente'] = '-';
            }
            $data['vagas'][$i]['overview']['value'] = $content->created_at->addDays(3)->format('d/m/Y');
            $data['vagas'][$i]['overview']['ref'] = \Carbon\Carbon::parse($content->created_at->addDays(3))->timestamp;
            if ($contentclient != null) {
                $data['vagas'][$i]['taxa'] = $contentclient->clientcondition()->first()->tax;
                $data['vagas'][$i]['faturamento'] = ($data['vagas'][$i]['vagas'] * ($data['vagas'][$i]['taxa'] / 100)) * $content->salary;
                $data['vagas'][$i]['faturamento'] = 'R$' . number_format(floatval($data['vagas'][$i]['faturamento']), 2, '.', '.');
            } else {
                $data['vagas'][$i]['faturamento'] = '-';
            }
            $data['vagas'][$i]['c/o'] = $content->getLikesCount() . '/' . $data['vagas'][$i]['vagas'];

            $i++;
        }
//        dd($data);
        return $data;
    }

    public function index(Request $request) {



        $data = array();

        if ($request->user()->admin == 1) {
            $vagas = $this->filters($request, \App\Models\Content::inkluaUsersContent());
        } else {

            $office = $request->user()->office();
            $data['escritorio'] = $office->name;

             $vagas = $this->filters($request, Content::where('office_id',$office->id));
            $vagas = $vagas->where('type',1);
            $vagas = $vagas->where('status','publicada');
            if ($request->exists('debug2')) {
                dd(Controller::getEloquentSqlWithBindings($vagas));
            }
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
            $contentclient = $content->contentclient();
            $data['vagas'][$i]['id'] = $content->id;
            $data['vagas'][$i]['titulo_vagas'] = $content->title;
            if ($contentclient != null) {
                $data['vagas'][$i]['vagas'] = $contentclient->vacancy;
            } else {
                $data['vagas'][$i]['vagas'] = '-';
            }

            $data['vagas'][$i]['criado_em']['value'] = $content->created_at->format('d/m/Y');
            $data['vagas'][$i]['criado_em']['ref'] = \Carbon\Carbon::parse($content->created_at)->timestamp;
            $data['vagas'][$i]['recrutador'] = $content->user()->first()->fullname();
            $data['vagas'][$i]['entrega']['value'] = $content->created_at->addDays(5)->format('d/m/Y');
            $data['vagas'][$i]['entrega']['ref'] = \Carbon\Carbon::parse($content->created_at->addDays(5))->timestamp;
            if ($contentclient != null) {
                $data['vagas'][$i]['cliente'] = $contentclient->client()->first()->formal_name;
            } else {
                $data['vagas'][$i]['cliente'] = '-';
            }
            $data['vagas'][$i]['overview']['value'] = $content->created_at->addDays(3)->format('d/m/Y');
            $data['vagas'][$i]['overview']['ref'] = \Carbon\Carbon::parse($content->created_at->addDays(3))->timestamp;
            if ($contentclient != null) {
                $data['vagas'][$i]['taxa'] = $contentclient->clientcondition()->first()->tax;
                $data['vagas'][$i]['faturamento'] = ($data['vagas'][$i]['vagas'] * ($data['vagas'][$i]['taxa'] / 100)) * $content->salary;
                $data['vagas'][$i]['faturamento'] = 'R$' . number_format(floatval($data['vagas'][$i]['faturamento']), 2, '.', '.');
            } else {
                $data['vagas'][$i]['faturamento'] = '-';
            }
            $data['vagas'][$i]['c/o'] = $content->getLikesCount() . '/' . $data['vagas'][$i]['vagas'];

            $i++;
        }
//        dd($data);
        return $data;
    }

    public function index_fechada(Request $request) {



        $data = array();

        if ($request->user()->admin == 1) {
            $vagas = $this->filters($request, \App\Models\Content::inkluaUsersContent());
        } else {

            $office = $request->user()->office();
            $data['escritorio'] = $office->name;

              $vagas = $this->filters($request, Content::where('office_id',$office->id));
            $vagas = $vagas->where('type',1);
            $vagas = $vagas->where('status','fechada');
            if ($request->exists('debug2')) {
                dd(Controller::getEloquentSqlWithBindings($vagas));
            }
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
            $contentclient = $content->contentclient();
            $data['vagas'][$i]['id'] = $content->id;
            $data['vagas'][$i]['titulo_vagas'] = $content->title;
            $data['vagas'][$i]['criado_em']['value'] = $content->created_at->format('d/m/Y');
            $data['vagas'][$i]['criado_em']['ref'] = \Carbon\Carbon::parse($content->created_at)->timestamp;
            if ($contentclient != null) {
                $data['vagas'][$i]['cliente'] = $contentclient->client()->first()->formal_name;
            } else {
                $data['vagas'][$i]['cliente'] = '-';
            }
            $data['vagas'][$i]['descritivo'] = route('vaga.descritivo', array('id' => $data['vagas'][$i]['id']));
            $data['vagas'][$i]['detalhes'] = route('vaga.detalhes', array('id' => $data['vagas'][$i]['id']));

            $i++;
        }
//        dd($data);
        return $data;
    }

    public function filters(Request $request, $vagas) {



        if ($request->exists('content_id')) {
            $vagas = $vagas->where('contents.id', '=', $request->input('content_id'));
        } else {
            if ($request->exists('date_start') && $request->exists('date_end')) {
                $date_start = Carbon\Carbon::createFromFormat('d/m/Y', $request->input('date_start'))->format('Y/m/d');
                $date_end = Carbon\Carbon::createFromFormat('d/m/Y', $request->input('date_end'))->format('Y/m/d');
                $vagas = $vagas->whereRaw('(contents.created_at between "' . $date_start
                        . '" and '
                        . '"' . $date_end . '"'
                        . ' or (status="publicada" and  contents.created_at  <= "' . $date_start . '")'
                        . ')');
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
            if ($request->exists('key')) {
                $vagas = $vagas->whereRaw('(contents.user_id in (select user_id as id from inklua_users where office_id = ?) or '
                        . 'contents.id in (select content_id as id from contents_client,clients where contents_client.client_id = clients.id and clients.formal_name like ? ) or '
                        . 'contents.title like ? or '
                        . 'contents.id = ?  or  '
                        . 'contents.user_id in (select user_id from inklua_users where user_id in (select id from users where name like ?))'
                        . ')',
                        array($request->input('key'),
                            '%' . $request->input('key') . '%', '%' . $request->input('key') . '%',
//                            '%' . $request->input('key') . '%',
                            $request->input('key'),
                            '%' . $request->input('key') . '%'
                        )
                );
            }
            if ($request->exists('status')) {
                $vagas = $vagas->where('contents.status', $request->input('status'));
            }
        }
        if ($request->exists('debug')) {
            dd(Controller::getEloquentSqlWithBindings($vagas));
        }



        return $vagas;
    }

}
