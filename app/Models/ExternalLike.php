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
use App\Models\Content;
use App\Models\CandidateHunting;
use Illuminate\Database\Eloquent\Factories\HasFactory;
       
class ExternalLike extends Model {
                
    protected $table = 'external_like';
    protected $fillable = [
        'likes',        
        'id',        
    ];
    
    static $rules = array(
        'job_id' => 'required|max:255'
    );
    
    public function content(){
        return Content::find($this->job_id);
    }
    
   
}
