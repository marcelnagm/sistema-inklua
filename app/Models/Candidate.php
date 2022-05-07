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
use \Illuminate\Support\Str;
use App\Models\CandidteRole;
use App\Models\CandidateEnglishLevel;
use App\Models\State;
use App\Models\CandidateStatus;
use App\Models\CandidateGender;
use App\Models\CandidateRace;

class Candidate extends Model {

    protected $table = 'candidate';
    protected $fillable = [
        'gid',
        'role_id',
        'title',
        'payment', 'state_id', 'city', 'remote', 'move_out',
        'description', 'tecnical_degree', 'superior_degree',
        'spec_degree', 'mba_degree', 'master_degree', 'doctor_degree',
        'english_level', 'full_name', 'cellphone', 'email', 'cv_url',
                   'pcd',
        'pcd_type_id',
        'pcd_details',
        'pcd_report',     
        'status_id', 'published_at','race_id','gender_id'
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'published_at'
    ];
   
    static $rules = array(
        'gid' => 'nullable',
        'name' => 'required|max:255',
        'surname' => 'required|max:255',
        'birth_date' => 'required',
        'cellphone' => 'required|max:255',
        'email' => 'required|max:255',
        'payment' => 'required|max:255',
        'portifolio_url' => 'nullable',
        'linkedin_url' => 'nullable',
        'pcd' => 'required|max:1',
        'cv_path' => 'nullable',
        'pcd_type_id' => 'nullable',
        'pcd_details' => 'nullable',
        'pcd_report' => 'nullable',
        'state_id' => 'required|max:255',
        'city_id' => 'required|max:255',
        'english_level' => 'required|max:1',
        'remote' => 'required|max:1',
        'move_out' => 'required|max:1',
    );
    

    public function __construct($param = null) {
        if ($param != null) {
            $this->gid = md5(random_int(1, 125) * time() . Str::random(20));
            $this->status_id = 3;
            parent::__construct($param);
        }
    }

    public function role() {
        return CandidateRole::find($this->role_id);
    }

    public function state() {
        return State::find($this->state_id);
    }
    public function status() {
        return CandidateStatus::find($this->status_id);
    }       

    public function english_level_obj() {
        return CandidateEnglishLevel::find($this->english_level);
    }
    
    public function race() {
        return CandidateRace::find($this->race_id);
    }
    public function gender() {
        return CandidateGender::find($this->gender_id);
    }
    
       function phone(){
     return $this->masc_tel($this->cellphone);
    }
    
    function masc_tel($TEL) {
    $tam = strlen(preg_replace("/[^0-9]/", "", $TEL));
      if ($tam == 13) { // COM CÓDIGO DE ÁREA NACIONAL E DO PAIS e 9 dígitos
      return "+".substr($TEL,0,$tam-11)."(".substr($TEL,$tam-11,2).")".substr($TEL,$tam-9,5)."-".substr($TEL,-4);
      }
      if ($tam == 12) { // COM CÓDIGO DE ÁREA NACIONAL E DO PAIS
      return "+".substr($TEL,0,$tam-10)."(".substr($TEL,$tam-10,2).")".substr($TEL,$tam-8,4)."-".substr($TEL,-4);
      }
      if ($tam == 11) { // COM CÓDIGO DE ÁREA NACIONAL e 9 dígitos
      return "(".substr($TEL,0,2).")".substr($TEL,2,5)."-".substr($TEL,7,11);
      }
      if ($tam == 10) { // COM CÓDIGO DE ÁREA NACIONAL
      return "(".substr($TEL,0,2).")".substr($TEL,2,4)."-".substr($TEL,6,10);
      }
      if ($tam <= 9) { // SEM CÓDIGO DE ÁREA
      return substr($TEL,0,$tam-4)."-".substr($TEL,-4);
      }
  }
  
public function payment_formatted(){
    return number_format($this->payment,0,'','.');
}
  
}
