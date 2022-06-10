<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;



/**
 * Report Parte do sistema hunting
 *
 * @author marcel
 */
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Support\Str;
use App\Models\CandidateHunting as Candidate;

class CandidateReport extends Model {

    protected $table = 'candidate_report';
    protected $fillable = [
        'candidate_id',
        'job_id',
        'hired',
        'owner',
        'obs',
        'company', 
        'report_status_id',
        'user_id'
    ];
    
    protected $dates = [
        'created_at',
        'updated_at'
    ];
        
    static $rules = array(
         'candidate_id' => 'nullable',
        'job_id' => 'nullable',
        'hired' => 'nullable',
        'owner' => 'nullable',
        'obs' => 'nullable',
        'company' => 'nullable', 
        'report_status_id' => 'nullable'        
    );
    
     public function user() {
        return Users::find($this->user_id);
    }
    
     public function candidate() {
        return Candidate::find($this->candidate_id);
    }
    
}
