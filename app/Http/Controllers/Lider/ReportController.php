<?php

namespace App\Http\Controllers\Lider;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\checkUserInkluer;
use Illuminate\Support\Facades\DB;
use App\Models\Content;
use App\Models\CandidateHunting;
use App\Models\InkluaUser;
use App\Models\InkluaOffice;
use Carbon;

class ReportController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth:api');
        $this->middleware('checkPjTypeUser');
        $this->middleware('App\Http\Middleware\checkUserInkluer');
    }

    
    public function index_produtidade(Request $request) {
        $data = array();
        $i = 1;
        if ($request->user()->admin == 1) {
            $users = $this->filtersUsers($request, InkluaUser::where('active',1)->selectRaw('distinct user_id,office_id'));
             $data['offices']= InkluaOffice::select('id','name')->get();
        } else {

            $office = $request->user()->office();
            $data['escritorio'] = $office->name;
            $users = $this->filtersUsers($request,$office->inkluaUsersActive());
        }   
    
       
        $data['amount']['total'] = 0;
        $data['amount']['produzidas']= 0;
        $data['amount']['fechadas'] = 0;
        $data['amount']['assertividade'] = 0;
//        dd($users);
       if($request->exists('debug2')) self::displayQuery($users);
       
            $users = $users->get();
        foreach ($users as $inkluaUser) {
            $data['recrutadores'][$i]['id'] = $inkluaUser->user()->id;
            $data['recrutadores'][$i]['name'] = $inkluaUser->user()->fullname() . '';
             if ($request->user()->admin == 1) {
                $data['recrutadores'][$i]['office']  = $inkluaUser->office().'';
             }
            $data['recrutadores'][$i]['posicoes'] = $this->filters($request, $inkluaUser->positionsTotal(), null)->get()->count();
            $data['amount']['total'] += $data['recrutadores'][$i]['posicoes'] ;   
            $data['recrutadores'][$i]['produzidas'] = $this->filters($request, $inkluaUser->positionsWithClient(), null)->get()->count();
            $data['amount']['produzidas'] +=$data['recrutadores'][$i]['produzidas'];
            $data['recrutadores'][$i]['fechadas'] = $this->filters($request, $inkluaUser->positionsClosed(), null)->get();            
            $data['recrutadores'][$i]['total'] = $inkluaUser->positionsSum($data['recrutadores'][$i]['fechadas']);            
            $data['recrutadores'][$i]['total'] = is_numeric($data['recrutadores'][$i]['total']) ? number_format($data['recrutadores'][$i]['total'], 2) : $data['recrutadores'][$i]['total'];            
            $data['recrutadores'][$i]['fechadas'] = $data['recrutadores'][$i]['fechadas']->count();
            $data['amount']['fechadas'] +=$data['recrutadores'][$i]['fechadas'];
            $data['recrutadores'][$i]['assertividade'] = $data['recrutadores'][$i]['posicoes'] > 0 ? $data['recrutadores'][$i]['fechadas'] / $data['recrutadores'][$i]['posicoes'] : '-';
            $data['amount']['assertividade'] += $data['recrutadores'][$i]['assertividade'] != '-' ?              $data['recrutadores'][$i]['assertividade'] : 0;
            $data['recrutadores'][$i]['assertividade'] = $data['recrutadores'][$i]['posicoes'] > 0 ? number_format($data['recrutadores'][$i]['assertividade'] * 100, 2) . '%' : '-';
            $i++;
        }
         $data['amount']['assertividade'] /=  $i;
         $data['amount']['assertividade'] = number_format($data['amount']['assertividade'] *100,2).'%';
        return array('data' => $data);
    }

//     Listagem para os lideres
    public function index_repos(Request $request) {



        $data = array();

        if ($request->user()->admin == 1) {
            $vagas = $this->filters($request, \App\Models\Content::inkluaUsersContent(), 'reposicao');
        } else {

            $office = $request->user()->office();
            $data['escritorio'] = $office->name;

            $vagas = $this->filters($request, Content::where('office_id', $office->id), 'reposicao');
            $vagas = $vagas->where('type', 1);
            if ($request->exists('debug2')) {
                dd(Controller::getEloquentSqlWithBindings($vagas));
            }
        }
        $vagas = $vagas->where('status', 'reposicao');
        $counter = $this->counter($request, $vagas);
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
            $data['vagas'][$i]['recrutador'] = $content->user()->first() != null ? $content->user()->first()->fullname() : '';
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
        return array_merge(array('data' => $data), $counter);
    }

    public function index_cliente(Request $request) {



        $data = array();

        if ($request->user()->admin == 1) {
            $vagas = $this->filters($request, Content::whereRaw('contents.id in (select job_id from candidate_report where report_status_id = 8)')
                            ->where('type', 1)
                    , 'com-cliente');
        } else {

            $office = $request->user()->office();
            $data['escritorio'] = $office->name;

            $vagas = $this->filters($request, Content::where('office_id', $office->id), 'com-cliente');
            $vagas = $vagas->where('type', 1);
        }
//        dd('va');
        $vagas = $vagas->whereRaw('contents.id in (select job_id from candidate_report where report_status_id = 8)');
        $counter = $this->counter($request, $vagas);
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
            $data['vagas'][$i]['recrutador'] = $content->user()->first() != null ? $content->user()->first()->fullname() : '';
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
                $data['vagas'][$i]['faturamento'] = $content->carteira();
                ;
                $data['vagas'][$i]['faturamento'] = 'R$' . number_format(floatval($data['vagas'][$i]['faturamento']), 2, '.', '.');
            } else {
                $data['vagas'][$i]['faturamento'] = '-';
            }
            $data['vagas'][$i]['c/o'] = $content->getLikesCount() . '/' . $data['vagas'][$i]['vagas'];

            $i++;
        }
//        dd($data);
        return array_merge(array('data' => $data), $counter);
    }

    public function index(Request $request) {



        $data = array();

        if ($request->user()->admin == 1) {
            $vagas = $this->filters($request, \App\Models\Content::inkluaUsersContent(), 'publicada');
        } else {

            $office = $request->user()->office();
            $data['escritorio'] = $office->name;

            $vagas = $this->filters($request, Content::where('office_id', $office->id), 'publicada');
            $vagas = $vagas->where('type', 1);

            if ($request->exists('debug2')) {
                dd(Controller::getEloquentSqlWithBindings($vagas));
            }
        }
        $vagas = $vagas->whereIn('status', array('reposicao', 'publicada'));
        $counter = $this->counter($request, $vagas);
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
            $data['vagas'][$i]['recrutador'] = $content->user()->first() != null ? $content->user()->first()->fullname() : '';
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
                $data['vagas'][$i]['faturamento'] = $content->carteira();
                $data['vagas'][$i]['faturamento'] = 'R$' . number_format(floatval($data['vagas'][$i]['faturamento']), 2, '.', '.');
            } else {
                $data['vagas'][$i]['faturamento'] = '-';
            }
            $data['vagas'][$i]['c/o'] = $content->getLikesCount() . '/' . $data['vagas'][$i]['vagas'];

            $i++;
        }
//        dd($data);
        return array_merge(array('data' => $data), $counter);
    }

    public function index_fechada(Request $request) {



        $data = array();

        if ($request->user()->admin == 1) {
            $vagas = $this->filters($request, \App\Models\Content::inkluaUsersContent(), 'fechada');
        } else {

            $office = $request->user()->office();
            $data['escritorio'] = $office->name;

            $vagas = $this->filters($request, Content::where('office_id', $office->id), 'fechada');
            $vagas = $vagas->where('type', 1);

            if ($request->exists('debug2')) {
                dd(Controller::getEloquentSqlWithBindings($vagas));
            }
        }
        $vagas = $vagas->where('status', 'fechada');
        $counter = $this->counter($request, $vagas);
        $vagas = $vagas->get()->skip(10 * ($request->input('page') - 1))->take(10);
//        dd($vagas );

        $i = 0;

        foreach ($vagas as $content) {
//        dd($i);
            $contentclient = $content->contentclient();
            $data['vagas'][$i]['id'] = $content->id;
            $data['vagas'][$i]['titulo_vagas'] = $content->title;
            $data['vagas'][$i]['recrutador'] = $content->user()->first() != null ? $content->user()->first()->fullname() : '';
            $data['vagas'][$i]['criado_em']['value'] = $content->created_at->format('d/m/Y');
            $data['vagas'][$i]['criado_em']['ref'] = \Carbon\Carbon::parse($content->created_at)->timestamp;
            if ($contentclient != null) {
                $data['vagas'][$i]['cliente'] = $contentclient->client()->first()->formal_name;
            } else {
                $data['vagas'][$i]['cliente'] = '-';
            }
            if ($contentclient != null) {
                $data['vagas'][$i]['total'] = $content->carteira();
                $data['vagas'][$i]['total'] = 'R$' . number_format(floatval($data['vagas'][$i]['total']), 2, '.', '.');
            } else {
                $data['vagas'][$i]['total'] = '-';
            }
            $data['vagas'][$i]['descritivo'] = route('vaga.descritivo', array('id' => $data['vagas'][$i]['id']));
            $data['vagas'][$i]['detalhes'] = route('vaga.detalhes', array('id' => $data['vagas'][$i]['id']));

            $i++;
        }
//        dd($data);
        return array_merge(array('data' => $data), $counter);
    }

    public function counter(Request $request, $vagas) {
        $data = array();
        $valo = clone $vagas;
        $valo = $valo->selectRaw('count(contents.status) as amount');
        $valo->groupby('status');
        if ($request->exists('debug5')) {
            dd(Controller::getEloquentSqlWithBindings($valo));
        }
        $data['amount'] = $valo->first() != null ? $valo->first()['amount'] : 0;
        ;
        $data['pages'] = array('page' => $request->input('page'),
            'per-page' => 10,
            'pages' => ceil($vagas->count() / 10)
        );
        return $data;
    }

    public function filtersUsers(Request $request, $vagas) {

            if ($request->exists('recruiter')) {
                $vagas = $vagas->whereRaw('user_id in (select user_id as id from inklua_users where  active =? and user_id in (select id as id from users where users.name like ?  or users.lastname like ?))', array($request->input('active',1),'%' . $request->input('recruiter') . '%', '%' . $request->input('recruiter') . '%'));
            }         
            if ($request->exists('office')) {
                $vagas = $vagas->whereRaw('user_id in (select user_id as id from inklua_users where office_id = ? and active=?)', array($request->input('office'),$request->input('active',1)));
            }
        
        $vagas = $vagas->orderby('created_at', $request->input('ordering_rule', 'ASC'));

        if ($request->exists('debug')) {
            dd(Controller::getEloquentSqlWithBindings($vagas));
        }



        return $vagas;
    }
    
    public function filters(Request $request, $vagas, $status) {



        if ($request->exists('content_id')) {
            $vagas = $vagas->where('contents.id', '=', $request->input('content_id'));
        } else {
            if ($request->exists('date_start') && $request->exists('date_end')) {
                $date_start = Carbon\Carbon::createFromFormat('d/m/Y', $request->input('date_start'))->format('Y/m/d');
                $date_end = Carbon\Carbon::createFromFormat('d/m/Y', $request->input('date_end'))->format('Y/m/d');
                if ($status == 'publicada')
                    $vagas = $vagas->whereRaw('(contents.created_at between "' . $date_start
                            . '" and '
                            . '"' . $date_end . '"'
                            . ' or ((status="publicada" or status="reposicao") and  contents.created_at  <= "' . $date_start . '")'
                            . ')');
                else
                    $vagas = $vagas->whereRaw('(contents.created_at between "' . $date_start
                            . '" and '
                            . '"' . $date_end . '")');
            }
            if ($request->exists('title')) {
                $vagas = $vagas->where('contents.title', 'like', '%' . $request->input('title') . '%');
            }
            if ($request->exists('client')) {
                $vagas = $vagas->whereRaw('contents.id in (select content_id as id from contents_client,clients where contents_client.client_id = clients.id and clients.formal_name like ? )', '%' . $request->input('client') . '%');
            }
            if ($request->exists('recruiter')) {
                $vagas = $vagas->whereRaw('user_id in (select id as id from users where users.name like ?  or users.lastname like ?)', array('%' . $request->input('recruiter') . '%', '%' . $request->input('recruiter') . '%'));
            }            
//            if ($request->exists('recruiter_name')) {
//                $vagas = $vagas->whereRaw('contents.user_id in (select id as id from users where users.name like ?  or users.lastname like ?)', array('%' . $request->input('recruiter_name') . '%', '%' . $request->input('recruiter_name') . '%'));
//            }            
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
        }
        $vagas = $vagas->orderby('created_at', $request->input('ordering_rule', 'ASC'));

        if ($request->exists('debug')) {
            dd(Controller::getEloquentSqlWithBindings($vagas));
        }



        return $vagas;
    }

}
