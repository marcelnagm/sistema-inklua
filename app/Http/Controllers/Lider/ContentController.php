<?php

namespace App\Http\Controllers\Lider;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\checkUserInkluer;
use Illuminate\Support\Facades\DB;
use App\Models\Content;
use Carbon;
use App\Models\User;
use App\Models\CandidateReport;

class ContentController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth:api');
        $this->middleware('checkPjTypeUser');
        $this->middleware('App\Http\Middleware\checkUserInkluer');
    }

    public function replacement(Request $request, $id) {
        $reports = CandidateReport::whereIn('id', explode(',', $request->input('report_id')))->get();
        $i =0;
        $j= 0;
        foreach ($reports as $report){
            if($report->candidate()->status != -9999) $i++;
            else {$report->replacement();
            $j++;
            }
        }
        return array('status' => true,'message' => 'Selecionado '.$reports->count().' candidatos [reposto -'.$j.', ignorados - '.$i.' ]!');
//        dd($reports);
        
    }
    
    public function selects(Request $request, $id) {
        $reports = CandidateReport::whereIn('id', explode(',', $request->input('report_id')))->get();
        foreach ($reports as $report){
            $report->report_status_id = 9;
            $report->save();
        }
        return array('status' => true,'message' => 'Selecionado '.$reports->count().' candidatos!');
//        dd($reports);
        
    }
    
    public function sendClient(Request $request, $id) {
        $reports = CandidateReport::where('report_status_id',9)
                ->where('job_id',$id)
                ->get();
        foreach ($reports as $report){
            $report->report_status_id = 8;
            $report->save();
        }
        return array('status' => true,'message' => 'Enviado '.$reports->count().' candidatos ao cliente!');
//        dd($reports);
        
    }
     
    public function details(Request $request, $id) {
        $content = Content::where('id', $id)->first();
        $contentclient = $content->contentclient();
        $data = array();
        $data['titulo_vaga'] = $content->title;
        if ($contentclient != null) {
            $data['vagas'] = $contentclient->vacancy;
        } else {
            $data['vagas'] = '-';
        }
        $data['entrega'] = $content->created_at->addDays(5)->format('d/m/Y');
        if ($contentclient != null) {
            $data['taxa'] = $contentclient->clientcondition()->first()->tax;
            $data['faturamento'] = ($data['vagas'] * ($data['taxa'] / 100)) * $content->salary;
            $data['faturamento'] = 'R$' . number_format(floatval($data['faturamento']), 2, '.', '.');
        } else {
            $data['faturamento'] = '-';
        }
         $data['c/o'] = $content->getLikesCount() . '/' . $data['vagas'];
         $data['status'] = $content->getStatusFront();
         $i=0;
         $report = new \App\Models\CandidateReport;
         foreach ($content->candidateReport() as $report){
             $candidate = $report->candidate();
             $data['candidate'][$i]['gid']=  $candidate->gid;
             $data['candidate'][$i]['name']=  $candidate->full_name();
             $data['candidate'][$i]['salary']=  $candidate->payment;
             $data['candidate'][$i]['status']=  $report->reportstatus().'';
             $data['candidate'][$i]['start_at']=  $report->start_at ? $report->start_at->format('d/m/Y') : '-';
             $data['candidate'][$i]['owner']=  $report->owner_formatted();
         }
          
        return $data;
    }

    public function description(Request $request) {
        
    }
    
    public function changeRecruiter(Request $request,$id ) {
          $user = User::findOrFail($request->input('recruiter_id'));
         $content = Content::where('id', $id)->first();
        $contentclient = $content->contentclient();
        $content->user_id = $user->id;
        $contentclient->user_id = $user->id;
        $content->save();
        $contentclient->save();
        return array('status' => true,'message' => 'Recrutador trocado com sucesso!');
    }
    
    

}
