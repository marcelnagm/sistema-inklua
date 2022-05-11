<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;



/**
 * Experience Parte do sistema hunting
 *
 * @author marcel
 */
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Support\Str;

class CandidateExperience extends Model {

    protected $table = 'candidate_experience_hunting';
    protected $fillable = [
        'candidate_id',
        'role',
        'company',
        'description',
        'start_at', 
        'end_at'
    ];
        
    static $rules = array(
        'role'=> 'required|max:255',
        'company' => 'required|max:255',
        'description' => 'nullable',
        'start_at'  => 'required', 
        'end_at' => 'nullable'        
    );
    
    
}
