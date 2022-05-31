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

class OfficeRole extends Model {
    protected $table = 'office_role';
    protected $fillable = [
        'role'
    ];
    
    protected $dates = [
        'created_at',
        'updated_at',
    ];

      static $rules = [
		'role' => 'required'
    ];
    
//    protected $hidden = [ ‘password’ ];
    
    public function __toString() {
        return $this->role;
    }
    
}
