<?php

namespace App\Http\Controllers\Recruiter;

use App\Models\CandidateExperience;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class CandidateExperienceControler extends Controller {


    /**
     *   Retorna um Json com todos os registos
     * @return Json 
     */
    public function index(Request $request) {        
        return CandidateExperience::where('candidate_id',$request->input('candidate_id'))->orderBy('start_at','ASC')->get();
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        return CandidateExperience::find( $id);
    }



}
