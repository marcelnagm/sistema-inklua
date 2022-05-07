<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

/**
 * Description of State
 *
 * @author marcel
 */
use Illuminate\Database\Eloquent\Model;

class CandidateGender extends Model {
    protected $table = 'candidate_gender';
    protected $fillable = [
        'name'
    ];
    
     static $rules = [
		'name' => 'required'
    ];

//    protected $hidden = [ ‘password’ ];
    
    public function __toString() {
        return $this->name;
    }
    
}
