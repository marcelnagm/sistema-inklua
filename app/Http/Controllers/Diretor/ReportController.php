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
        
        $cand =CandidateHunting::where('id','<>',null);

        $cand_pcd = CandidateHunting::where('pcd',1);
        
        $data['total_usuarios'] = $cand->count();
        $data['novos_usuarios']['value'] = $this->filter_date($request, $cand)->count();
        $data['novos_usuarios']['perc'] =  number_format($data['novos_usuarios']['value'] / $data['total_usuarios'] *100,2);
        $data['em_processo']['value'] = CandidateHunting::where('status','<>',-1)->where('status','<>',null)->count();
        $data['em_processo']['perc'] = number_format($data['em_processo']['value'] / $data['total_usuarios'] *100,2);
        $data['total_login']['value'] = User::lastLogin($request)->count();
        $data['total_login']['perc'] = number_format($data['total_login']['value'] / $data['total_usuarios'] *100,2);
        $data['total_likes'] = $this->filter_date($request, JobLike::where('id','<>',null))->count();

        $data_pcd = array();
        $data_pcd['total_usuarios'] = $cand_pcd->count();
        $data_pcd['novos_usuarios']['value'] = $this->filter_date($request, $cand_pcd)->count();
        $data_pcd['novos_usuarios']['perc'] =  number_format($data_pcd['novos_usuarios']['value'] / $data_pcd['total_usuarios'] *100,2);
        $data_pcd['em_processo']['value'] = $cand_pcd->where('status','<>',-1)->where('status','<>',null)->count();
        $data_pcd['em_processo']['perc'] = number_format($data_pcd['em_processo']['value'] / $data_pcd['total_usuarios'] *100,2);
        $data_pcd['total_login']['value'] = User::lastLogin($request,true)->count();
        $data_pcd['total_login']['perc'] = number_format($data_pcd['total_login']['value'] / $data_pcd['total_usuarios'] *100,2);
        $data_pcd['total_likes'] = $this->filter_date($request, JobLike::where('id','<>',null))->count();

//      dd($data_pcd);
        return array('data' => array(
            'geral' => $data,
            'pcd' => $data_pcd
            ) );
    }

}
