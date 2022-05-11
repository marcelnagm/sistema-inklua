<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;


/**
 * JobLike Parte do sistema hunting
 *
 * @author marcel
 */
use Illuminate\Database\Eloquent\Model;
use App\Mail\NotifyMail;

class JobLike extends Model {
    
    protected $table = 'job_like';
    protected $fillable = [
        'candidate_id',        
        'job_id',        
    ];
    
    static $rules = array(
        'job_id' => 'required|max:255'
    );
    
    public function notify() {
              Mail::to('receiver-email-id')->send(new NotifyMail());
 
      if (Mail::failures()) {
           return response()->Fail('Sorry! Please try again latter');
      }else{
           return response()->success('Great! Successfully send in your mail');
         }
     
        
    }
    
}
