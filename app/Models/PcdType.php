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

class PcdType extends Model {
    protected $table = 'pcd_type';
    protected $fillable = [
        'type',
        
    ];
    
//    protected $hidden = [ ‘password’ ];
    
    public function __toString() {
        return $this->type;
    }
    
}
