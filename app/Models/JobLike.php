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
   
    public function candidate() {
        return CandidateHunting::find($this->candidate_id);
    }
    
    public function content(){
        return Content::find($this->content_id);
    }
    
    public function toArray() {
//        parent::toArray();
//          id: 1,
//        age: 18,
//        salary_expectation: 5000,
//        education_level: "Fundamental Completo",
//        disability: "Deficiência múltipla",
//        last_experience: {
//          title: "Auxiliar Administrativo",
//        },
//        state: "SP",
//        city: "São Paulo",
//        status: "TAKEN",
//        name: "João",
//        recruiter: { name: "Jorge Amado" },
//      }
        
       $candidate = $this->candidate(); 
       $last_experience = $candidate->last_experience();
       
//       dd($last_experience);
      return array(
          'id' => $this->id,
          'gid' => $this->gid,
          'age' => $candidate->age(),
          'salary' => $candidate->payment,
          'education_level' => LevelEducation::find($candidate->education_max()).'' ,
          'disability' => $candidate->pcd_typo().'' ,
          'last_experience' => array('title' => $last_experience ? $last_experience->role : 'Nenhum' ) ,
          'state' => $candidate->state()->UF,
          'city' => $candidate->city()->name,
          'status' => $candidate->status_name(),
          'name' => $candidate->full_name()
          
          
      ) ; 
        
        
        
    }
    
}
