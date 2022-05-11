<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;



/**
 *  Education  Parte do sistema hunting
 *
 * @author marcel
 */
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Support\Str;

class CandidateEducation extends Model {

    protected $table = 'candidate_education_hunting';
    protected $fillable = [
        'candidate_id',
        'level_education_id',
        'institute',
        'course',
        'start_at', 
        'end_at'
    ];
        
    static $rules = array(
        'level_education_id'=> 'required|max:255',
        'institute' => 'required|max:255',
        'course' => 'required|max:255',
        'start_at' => 'required|max:255', 
        'end_at' => 'nullable'        
    );
    
     public function level_education() {
        return LevelEducation::find($this->level_education_id);
    }
 
    public function toArray() {
       $data = parent::toArray();
       $data['level_education_id'] = $this->level_education()."";
     return $data;   
    }
    
    
}
