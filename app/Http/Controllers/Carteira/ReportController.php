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
        $valo = DB::select('       select status, sum(carteira) from (
SELECT contents.id, contents.salary, (contents.salary * (client_condition.tax / 100)) as "carteira", contents.status  FROM `contents`,contents_client,client_condition WHERE contents.id = contents_client.content_id and  client_condition.id = contents_client.client_condition_id
and contents.user_id in (select user_id from inklua_users where office_id = :office and active = 1)

) as a group by status   ', array('office' => $office->id));

        $data['escritorio'] = $office->name;
        $data['carteira'] = $valo;
// dd($data);
//       dd($office->inkluaUsersContent()->count());
        $i = 0;
        $vagas = $office->inkluaUsersContent()->get()->skip(10 * $request->input('page'))->take(10);
        foreach ($vagas as $content) {
//        dd($i);
            $data['vagas'][$i]['id'] = $content->id;
            $data['vagas'][$i]['status'] = $content->getStatusName();
            $data['vagas'][$i]['titulo_vagas'] = $content->title;
            $data['vagas'][$i]['criado_em'] = $content->created_at->format('d/m/Y');
            $data['vagas'][$i]['salario'] = $content->salary;
            $contentclient = $content->contentclient();
            $data['vagas'][$i]['posicoes'] = $contentclient->vacancy;
            $data['vagas'][$i]['taxa'] = $contentclient->clientcondition()->first()->tax;
            $data['vagas'][$i]['cliente'] = $contentclient->client()->first()->formal_name;
            $data['vagas'][$i]['recrutador'] = $content->user()->first()->fullname();
            $data['vagas'][$i]['carteira'] = $data['vagas'][$i]['posicoes'] * ($data['vagas'][$i]['taxa'] / 100) * $data['vagas'][$i]['salario'];
            $i++;
        }

        dd($data);
        return $data;
    }

}
