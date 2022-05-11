<?php

namespace App\Http\Controllers\Recruiter;

use App\Models\CandidateEducation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class CandidateEducationControler extends Controller {


    /**
     *   Retorna um Json com todos os registos
     * @return Json 
     */
    public function index(Request $request) {        
        return CandidateEducation::where('candidate_id',$request->input('candidate_id'))
                ->orderBy('level_education_id','DESC')
                ->orderBy('end_at','DESC')
                
                ->get();
    }
    

}
