<?php

namespace App\Http\Controllers\Hunting\Candidate;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\CandidateHunting as Candidate;
use App\Models\InkluaUser;

class CandidateControler extends Controller {

    

    public function store(Request $request) {



//          $messsages = array(
//		'email.required'=>'You cant leave Email field empty',
//		'name.required'=>'You cant leave name field empty',
//                'name.min'=>'The field has to be :min chars long',
//	);

        $data = $this->validate($request, Candidate::$rules);
//        dd ($data);
//	$validator = Validator::make(Input::all(), $rules,$messsages);

        $cand = new Candidate($data);
        $cand->save_pcd_report($data['pcd_report'], $request->input('pcd_report_ext'));
        unset($data['pcd_report']);
        $cand->save_cv_path($data['cv_path'], $request->input('cv_path_ext'));
        unset($data['cv_path']);
                $user = auth()->guard('api')->user();
        $cand->user_id = $user->id;
        $cand->save();

        return response()->json([
                    'status' => true,
                    'msg' => 'Candidadte successfully added!',
        ]);
    }


    public function update(Request $request, $id) {
        $cand = Candidate::where('gid', $id)->first();
        $data = $this->validate($request, Candidate::$rules);
//        dd ($cand);
//	$validator = Validator::make(Input::all(), $rules,$messsages);




        $cand->save_pcd_report($data['pcd_report'], $request->input('pcd_report_ext'));
        unset($data['pcd_report']);
        $cand->save_cv_path($data['cv_path'], $request->input('cv_path_ext'));
        unset($data['cv_path']);
        $cand->update($data);

        return response()->json([
                    'status' => true,
                    'msg' => 'Candidate successfully updated!',
        ]);
    }

    /**
     * Remove o candidato especificado
     *     
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if (InkluaUser::isInternal(Auth::user()->id)) {
            return $candidate = Candidate::where('gid', $id)->first()->delete();
        }
    }

}
