<?php

namespace App\Http\Controllers\Hunting\Recruiter;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\JobLike;
use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\InkluaUser;
use App\Models\User;
use App\Models\Candidate;
use App\Mail\NotifyMail;

class JobLikeControler extends Controller {


    public function index($id) {
           $user = auth()->guard('api')->user();
//        dd(Content::find($id)->user_id ,$user->id);
        if (Content::find($id)->user_id != $user->id) {
             return response()->json([
                        'status' => false,
                        'msg' => 'Esta vaga não é sua ç)!',
            ]);
        }

        if (InkluaUser::isInternal($user->id)) {
            return Candidate::whereIn('id', JobLike::where('job_id', $id)->orderBy('created_at')->pluck('candidate_id'))->get();                        
        } else {
            return JobLike::where('job_id', $id)->orderBy('created_at')->count();
        }
    }

    /*
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response retorna um json notificando que foi criado ou uma mensagem de erro de validação de campo
     */

//
//    /**
//     * Remove o candidato especificado
//     *     
//     * @return \Illuminate\Http\Response
//     */
//    public function destroy($id) {
//
//        $candidate = JobLike::find($id)->delete();
//        if ($candidate) {
//            return response()->json([
//                        'status' => true,
//                        'msg' => 'Job Unliked!',
//            ]);
//        } else {
//            return response()->json([
//                        'status' => true,
//                        'msg' => 'Request Failed',
//            ]);
//        }
//    }
}
