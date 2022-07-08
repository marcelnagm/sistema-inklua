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
use App\Model\State;

class LevelEducation extends Model {
    
    protected $table = 'level_education';
    protected $fillable = [
        'name'        
    ];
    
//    protected $hidden = [ ‘password’ ];
    
        public function __toString() {
        return ucfirst($this->name);
    }

    
    
}
