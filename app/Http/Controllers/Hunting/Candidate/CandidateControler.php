<?php

namespace App\Http\Controllers\Hunting\Candidate;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\CandidateHunting as Candidate;
use App\Models\InkluaUser;
use Illuminate\Support\Facades\Validator;

class CandidateControler extends Controller {

    public function __construct() {
        $this->middleware('App\Http\Middleware\checkUserCandidate', ['only' => ['update', 'destroy']]);
    }

    public function store(Request $request) {


//          $messsa';ges = array(
//		'email.required'=>'You cant leave Email field empty',
//		'name.required'=>'You cant leave name field empty',
//                'name.min'=>'The field has to be :min chars long',
//	);
//        dd($request);

        $user = auth()->guard('api')->user();
        if ($user->candidatehunting() == null) {
            $data = $request->all();
            $validator = $this->validator($data, Candidate::$rules);
            if ($validator->fails()) {
                return response()->json(
                                [
                                    "errors" => $validator->messages()
                                ], 400, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                                JSON_UNESCAPED_UNICODE
                );
            }
//        dd ($data->all());
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
        } else {
            return response()->json([
                        'status' => false,
                        'msg' => 'Você deve atualizar seu cadastro, você ja possui um cadastro',
            ]);
        }
    }

    public function update(Request $request) {
        $user = auth()->guard('api')->user();
        $cand = Candidate::where('user_id', $user->id)->first();
        $data = $request->all();
        $validator = $this->validator($data, Candidate::$rules);
        if ($validator->fails()) {
            return response()->json(
                            [
                                "errors" => $validator->messages()
                            ], 400, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                            JSON_UNESCAPED_UNICODE
            );
        }
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
//        if (InkluaUser::isInternal(Auth::user()->id)) {
        $user = auth()->guard('api')->user();
        return $candidate = Candidate::where('user_id', $user->id)->first()->delete();
//        }
    }

    public function validator($data) {
        $response = Validator::make($data, Candidate::$rules);

        return $response;
    }

}
