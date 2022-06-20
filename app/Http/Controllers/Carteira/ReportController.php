<?php

namespace App\Http\Controllers\Carteira;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\checkUserInkluer;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth:api');
        $this->middleware('checkPjTypeUser');
        $this->middleware('App\Http\Middleware\checkUserInkluer');
    }

    public function index(Request $request) {



        $data = array();
        $office = $request->user()->office();
        $valo = DB::select('select status, sum(carteira) as total,count(status) as count from (
SELECT contents.id, contents.salary, (contents.salary * (client_condition.tax / 100))* contents_client.vacancy as "carteira", contents.status  FROM `contents`,contents_client,client_condition WHERE contents.id = contents_client.content_id and  client_condition.id = contents_client.client_condition_id
and contents.user_id in (select user_id from inklua_users where office_id = :office and active = 1)

) as a group by status   ', array('office' => $office->id));

        $data['escritorio'] = $office->name;
        $data['carteira'] = $valo;
// dd($data);
        $i = 0;

        $vagas = $this->filters($request, $office->inkluaUsersContent());
//        dd($vagas );
        foreach ($vagas as $content) {
//        dd($i);
            $data['vagas'][$i]['id'] = $content->id;
            $data['vagas'][$i]['status'] = $content->getStatusName();
            $data['vagas'][$i]['titulo_vagas'] = $content->title;
            $data['vagas'][$i]['criado_em'] = $content->created_at->format('d/m/Y');
            $data['vagas'][$i]['salario'] = $content->salary;
            $contentclient = $content->contentclient();
            if ($contentclient == null) {
                return response()->json([
                            'error' => 'Não existe associação de cliente com o content_id -' . $content->id,
                                ], 500);
            }

            $data['vagas'][$i]['posicoes'] = $contentclient->vacancy;
            $data['vagas'][$i]['taxa'] = $contentclient->clientcondition()->first()->tax;
            $data['vagas'][$i]['cliente'] = $contentclient->client()->first()->formal_name;
            $data['vagas'][$i]['recrutador'] = $content->user()->first()->fullname();
            $data['vagas'][$i]['carteira'] = $data['vagas'][$i]['posicoes'] * ($data['vagas'][$i]['taxa'] / 100) * $data['vagas'][$i]['salario'];
            $i++;
        }

//        dd($data);
        return $data;
    }

    public function filters(Request $request, $vagas) {



        if ($request->exists('content_id')) {
            $vagas = $vagas->where('id', '=', $request->input('content_id'));
        } else {
            if ($request->exists('date_start')) {
                $vagas = $vagas->where('created_at', '>=', $request->input('date_start'));
            }
            if ($request->exists('date_end')) {
                $vagas = $vagas->where('created_at', '<=', $request->input('date_end'));
            }
            if ($request->exists('title')) {
                $vagas = $vagas->where('title', 'like', '%' . $request->input('title') . '%');
            }
            if ($request->exists('client')) {
                $vagas = $vagas->whereRaw('id in (select content_id as id from contents_client,clients where contents_client.client_id = clients.id and clients.formal_name like ? )', '%' . $request->input('client') . '%');
            }
            if ($request->exists('recruiter')) {
                $vagas = $vagas->whereRaw('user_id in (select id as id from users where users.name like ?  or users.lastname like ?)', array('%' . $request->input('client') . '%', '%' . $request->input('client') . '%'));
            }
        }
        if($request->exists('debug')){
        dd(Controller::getEloquentSqlWithBindings($vagas));
        }

        $vagas = $vagas->get()->skip(10 * ($request->input('page') - 1))->take(10);

        return $vagas;
    }

}
