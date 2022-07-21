<?php

namespace App\Http\Controllers\Diretor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\checkUserInkluer;
use Illuminate\Support\Facades\DB;
use App\Models\Content;
use App\Models\CandidateHunting;
use App\Models\User;
use App\Models\JobLike;
use Carbon;

class ReportController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth:api');
        $this->middleware('checkAdminPermission');
    }

//  Relatorios de produtividade e engajamento

    public function index_engajamento(Request $request) {
        $data = array();
        $data['total_usuarios'] = CandidateHunting::where('id','<>',null)->count();
        $data['novos_usuarios']['value'] = $this->filter_date($request, CandidateHunting::where('id','<>',null))->count();
        $data['novos_usuarios']['perc'] =  number_format($data['novos_usuarios']['value'] / $data['total_usuarios'] *100,2);
        $data['em_processo']['value'] = CandidateHunting::where('id','<>',-1)->where('id','<>',null)->count();
        $data['em_processo']['perc'] = number_format($data['em_processo']['value'] / $data['total_usuarios'] *100,2);
        $data['total_login']['value'] = User::lastLogin($request)->count();
        $data['total_login']['perc'] = number_format($data['total_login']['value'] / $data['total_usuarios'] *100,2);
        $data['total_likes'] = $this->filter_date($request, JobLike::where('id','<>',null))->count();

      
        return array('data' => $data);
    }

}
