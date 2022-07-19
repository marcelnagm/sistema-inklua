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
        $this->middleware('checkAdminPermission');
    }

//  Relatorios de produtividade e engajamento

    public function index_produtidade(Request $request) {
        $data = array();
        $i = 0;
        if ($request->user()->admin == 1) {
            $users = \App\Models\InkluaUser::all();
        } else {

            $office = $request->user()->office();
            $data['escritorio'] = $office->name;
            $users = $office->inkluaUsers()->get();
        }
        foreach ($users as $inkluaUser) {
            $data['recrutadores'][$i]['id'] = $inkluaUser->user()->id;
            $data['recrutadores'][$i]['name'] = $inkluaUser->user()->fullname() . '';
            $data['recrutadores'][$i]['posicoes'] = $this->filters($request, $inkluaUser->positionsTotal())->get()->count();
            $data['recrutadores'][$i]['com_cliente'] = $this->filters($request, $inkluaUser->positionsWithClient())->get()->count();
            $data['recrutadores'][$i]['fechadas'] = $this->filters($request, $inkluaUser->positionsClosed())->get();
            $data['recrutadores'][$i]['total'] = $inkluaUser->positionsSum($data['recrutadores'][$i]['fechadas']);
            $data['recrutadores'][$i]['fechadas'] = $data['recrutadores'][$i]['fechadas']->count();
            $data['recrutadores'][$i]['assertividade'] = $data['recrutadores'][$i]['posicoes'] > 0 ? $data['recrutadores'][$i]['fechadas'] / $data['recrutadores'][$i]['posicoes'] : '-';
            $data['recrutadores'][$i]['assertividade'] = $data['recrutadores'][$i]['posicoes'] > 0 ? number_format($data['recrutadores'][$i]['assertividade'] * 100, 2) . '%' : '-';
            $i++;
        }

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
