<?php

namespace App\Http\Controllers\Carteira;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\checkUserInkluer;

class ReportController extends Controller
{
    //
      public function __construct() {
        $this->middleware('auth:api');
        $this->middleware('checkPjTypeUser');
        $this->middleware('App\Http\Middleware\checkUserInkluer');
    }

    
      public function index(Request $request) {
       $data = array ();
       $office = $request->user()->office();
       $data['escritorio']  =  $office->name;
       
//       dd($office->inkluaUsersContent()->count());
       $i = 0;
      foreach($office->inkluaUsersContent()->get() as $content){
//        dd($i);
       $data['vaga'][$i]['id'] = $content->id;
       $data['vaga'][$i]['status'] = $content->getStatusName();
       $data['vaga'][$i]['titulo_vaga'] = $content->title;
       $data['vaga'][$i]['criado_em'] = $content->created_at->format('d/m/Y');
       $data['vaga'][$i]['salario'] = $content->salary;
       $contentclient  = $content->contentclient();
       $data['vaga'][$i]['posicoes'] = $contentclient->vacancy;
       $data['vaga'][$i]['taxa'] = $contentclient->clientcondition()->first()->tax;
       $data['vaga'][$i]['cliente'] = $contentclient->client()->first()->formal_name;
       $data['vaga'][$i]['recrutador'] = $content->user()->first()->fullname();
       $data['vaga'][$i]['carteira'] = $data['vaga'][$i]['posicoes']  *  ($data['vaga'][$i]['taxa'] /100)*   $data['vaga'][$i]['salario'];
       $i++;
      }
       
       dd($data);   
        return $data;
      }
}
