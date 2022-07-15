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

class ReportStatus extends Model {
    protected $table = 'report_status';
    protected $fillable = [
      'status_front',  'status',
        
    ];
    
//    protected $hidden = [ ‘password’ ];
    
    public function __toString() {
        return $this->status;
    }
    
    static function byStatusFront($status_name){
        return self::where('status_front',$status_name)->first();
    }
    
}
