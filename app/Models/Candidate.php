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
use App\Models\PcdType;
use Illuminate\Support\Facades\Storage;

class Candidate extends Model {

    protected $table = 'candidate';
    protected $fillable = [
        'gid',
        'role_id',
        'title',
        'payment', 'state_id', 'city', 'remote', 'move_out',
        'description', 'tecnical_degree', 'superior_degree',
        'CID',
        'spec_degree', 'mba_degree', 'master_degree', 'doctor_degree',
        'english_level', 'full_name', 'cellphone', 'email', 'cv_url',
        'pcd',
        'pcd_type_id',
        'pcd_details',
        'pcd_report',
        'english_level',
        'full_name',
        'cellphone',
        'email',
        'cv_url',
        'status_id', 'published_at', 'race_id', 'gender_id'
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'published_at'
    ];
    static $rules = array(
        'gid' => 'nullable',
        'title' => 'required|max:255',
        'role_id' => 'required|max:255',
        'status_id' => 'required|max:255',
        'cellphone' => 'required|max:255',
        'email' => 'required|max:255',
        'payment' => 'required|max:255',
        'portifolio_url' => 'nullable',
        'CID' => 'nullable',
        'description' => 'required',
        'tecnical_degree' => 'nullable',
        'superior_degree' => 'nullable',
        'spec_degree' => 'nullable',
        'mba_degree' => 'nullable',
        'master_degree' => 'nullable',
        'doctor_degree' => 'nullable',
        'linkedin_url' => 'nullable',
        'pcd' => 'required|max:1',
        'cv_url' => 'nullable',
        'pcd_type_id' => 'nullable',
        'pcd_details' => 'nullable',
        'pcd_report' => 'nullable',
        'state_id' => 'required|max:255',
        'city' => 'required|max:255',
        'full_name' => 'required|max:255',
        'cellphone' => 'required|max:255',
        'email' => 'required|max:255',
        'english_level' => 'required|max:1',
        'remote' => 'required|max:1',
        'move_out' => 'required|max:1',
        'race_id' => 'required|max:2',
        'gender_id' => 'required|max:2',
    );
    static $admin = array('full_name', 'cellphone', 'email');
    static $public = array(
        'cellphone',
        'created_at',
        'cv_url',
        'email',
        'full_name',
        'id',
        'remote',
        'role_id',
        'status_id',
        'race_id',
        'gender_id',
        'pcd',
        'pcd_details',
        'pcd_report'
    );

    public function __construct($param = null) {
        if ($param == null) {
            $this->gid = md5(random_int(1, 125) * time() . Str::random(20));
            $this->status_id = 3;
            parent::__construct();
        } else {
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

      public function user() {
        return User::find($this->user_id);
    }

    
    public function race() {
        return CandidateRace::find($this->race_id);
    }

    public function gender() {
        return CandidateGender::find($this->gender_id);
    }

    function phone() {
        return $this->masc_tel($this->cellphone);
    }

    function masc_tel($TEL) {
        $tam = strlen(preg_replace("/[^0-9]/", "", $TEL));
        if ($tam == 13) { // COM CÓDIGO DE ÁREA NACIONAL E DO PAIS e 9 dígitos
            return "+" . substr($TEL, 0, $tam - 11) . "(" . substr($TEL, $tam - 11, 2) . ")" . substr($TEL, $tam - 9, 5) . "-" . substr($TEL, -4);
        }
        if ($tam == 12) { // COM CÓDIGO DE ÁREA NACIONAL E DO PAIS
            return "+" . substr($TEL, 0, $tam - 10) . "(" . substr($TEL, $tam - 10, 2) . ")" . substr($TEL, $tam - 8, 4) . "-" . substr($TEL, -4);
        }
        if ($tam == 11) { // COM CÓDIGO DE ÁREA NACIONAL e 9 dígitos
            return "(" . substr($TEL, 0, 2) . ")" . substr($TEL, 2, 5) . "-" . substr($TEL, 7, 11);
        }
        if ($tam == 10) { // COM CÓDIGO DE ÁREA NACIONAL
            return "(" . substr($TEL, 0, 2) . ")" . substr($TEL, 2, 4) . "-" . substr($TEL, 6, 10);
        }
        if ($tam <= 9) { // SEM CÓDIGO DE ÁREA
            return substr($TEL, 0, $tam - 4) . "-" . substr($TEL, -4);
        }
    }

    public function payment_formatted() {
        return number_format($this->payment, 0, '', '.');
    }

    public function pcd_typo() {
        return $this->pcd_type_id != null ? PcdType::find($this->pcd_type_id) : "Nenhum";
    }

    public function save_pcd_report($pcd_report, $ext) {

        if (Storage::exists("docs/$this->gid"))
            Storage::makeDirectory("docs/$this->gid");

        Storage::disk('local')->put("docs/$this->gid/pcd_report.$ext", base64_decode($pcd_report));
        $this->pcd_report = "docs/$this->gid/pcd_report.$ext";
    }

    public function toArray($public = false) {
        
        $data = parent::toArray();
       
        if ($public == true) {
            foreach (Candidate::$public as $u) {
                unset($data[$u]);
            }
        }
        if ($this->pcd_type_id != null) {
            $data['pcd_type_id'] = $this->pcd_typo()->type;
        } else
            $data['pcd_type_id'] = 'Nennhuma';
        
        return $data;
    }

    public function save(array $attributes = [], array $options = []) {

        if (isset($attributes['payment'])) {
            if (str_contains($attributes['payment'], '.'))
                $attributes['payment'] = $attributes['payment'] * 1000;
        }
        parent::save($attributes, $options);
    }

    public function update(array $attributes = [], array $options = []) {
//        dd($attributes);

        if (isset($attributes['payment'])) {
            if (str_contains($attributes['payment'], '.'))
                $attributes['payment'] = $attributes['payment'] * 1000;
        }

        parent::update($attributes, $options);
    }

}
