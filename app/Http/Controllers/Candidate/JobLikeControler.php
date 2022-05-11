<?php

namespace App\Http\Controllers\Candidate;

use App\Models\JobLike;
use Illuminate\Http\Request;
use App\Mail\NotifyMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Content;
use App\Models\InkluaUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class JobLikeControler extends Controller {

    /**
     *   Retorna um Json com todos os registos
     * @return Json 
     */
    public function index(Request $request) {
        $user = auth()->guard('api')->user();
        return JobLike::where('candidate_id', $user->id)->orderBy('created_at')->get();
    }

    public function store(Request $request) {
$user = auth()->guard('api')->user();
        $count = JobLike::where('candidate_id', $user->id)
                        ->where('job_id', $request->input('job_id'))->count();

        if ($count == 0) {
            $data = $this->validate($request, JobLike::$rules);
//        dd ($data);
//	$validator = Validator::make(Input::all(), $rules,$messsages);

            $job = Content::find($data['job_id']);

            
            
            $cand = new JobLike($data);
            $cand->candidate_id = $user->id;
            $cand->save();
//            if (InkluaUser::isInternal($job->user_id)) {
//                $user = User::find($job->user_id);
//                $email = "$user->email";
                
//                $count = JobLike::where('job_id', $data['job_id'])->count();
//                Mail::to($email)->send(new NotifyMail($count));

                
//            }
            return response()->json([
                        'status' => true,
                        'msg' => 'Job Liked!',
            ]);
        } else {
            return response()->json([
                        'status' => false,
                        'msg' => 'Job ja Liked!',
            ]);
        }
    }

    
     public function exist(Request $request) {
         $user = auth()->guard('api')->user();
        $count = JobLike::where('candidate_id', $user->id)
                        ->where('job_id', $request->input('job_id'))->count();

        if ($count == 0) {
                
            
            return response('false',404 );
        } else {
            return response('true',200);
        }
    }
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
