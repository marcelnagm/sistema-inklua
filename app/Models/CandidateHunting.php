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
use Illuminate\Support\Facades\Storage;
use App\Models\CandidateReport;

class CandidateHunting extends Model {

    protected $table = 'candidate_hunting';
    protected $fillable = [
        'gid',
        'name',
        'gid',
        'surname',
        'birth_date',
        'cellphone',
        'email',
        'payment',
        'portifolio_url',
        'linkedin_url',
        'pcd',
        'pcd_type_id',
        'pcd_details',
        'pcd_report',
        'state_id',
        'city_id',
        'remote', 'move_out',
        'english_level'
    ];
    protected $required = array(
        'gid' => 'required|max:255',
        'name' => 'required|max:255',
        'surname' => 'required|max:255',
        'birth_date' => 'required',
        'cellphone' => 'required|max:255',
        'email' => 'required|max:255',
        'payment' => 'required|max:255',
        'portifolio_url' => 'required|max:255',
        'linkedin_url' => 'required|max:255',
        'pcd' => 'required|max:1',
        'pcd_type_id' => 'nullable',
        'pcd_details' => 'nullable',
        'pcd_report' => 'nullable',
        'english_level' => 'nullable',
        'state_id' => 'required|max:255',
        'city_id' => 'required|max:255',
        'remote' => 'required|max:1',
        'move_out' => 'required|max:1',
    );
    protected $dates = [
        'created_at',
        'updated_at'
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

//    protected $hidden = [ ‘password’ ];

    public function __construct($param = null) {
        if ($param != null) {
            $this->gid = md5(random_int(1, 125) * time() . Str::random(20));
            parent::__construct($param);
        }
    }

    public function english_level_obj() {
        return CandidateEnglishLevel::find($this->english_level);
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

    public function state() {
        return State::find($this->state_id);
    }

    public function city() {
        return City::find($this->city_id);
    }
        
    public function pcd_typo() {
        return $this->pcd_type_id != null? PcdType::find($this->pcd_type_id) : "Nenhum";
    }

    public function education() {
        return CandidateEducation::where('candidate_id', $this->id)->orderBy('level_education_id')->get();
    }

    public function report() {
        return CandidateReport::where('candidate_id', $this->id)->orderBy('updated_at','DESC')->get();
    }

    public function experience() {
        return CandidateExperience::where('candidate_id', $this->id)->orderBy('end_at', 'ASC')->get();
    }

    public function save_pcd_report($pcd_report, $ext) {

        if (Storage::exists("docs/$this->gid"))
            Storage::makeDirectory("docs/$this->gid");

        Storage::disk('local')->put("docs/$this->gid/pcd_report.$ext", base64_decode($pcd_report));
        $this->pcd_report = "docs/$this->gid/pcd_report.$ext";
    }

    public function save_cv_path($cv_path, $ext) {

        if (Storage::exists("docs/$this->gid"))
            Storage::makeDirectory("docs/$this->gid");

        Storage::disk('local')->put("docs/$this->gid/cv.$ext", base64_decode($cv_path));
        $this->cv_path = "docs/$this->gid/pcd_report.$ext";
    }

    public function compact() {
        $data = $this->toArray();
        $data['education'] = $this->education()->toArray();
        $data['experience'] = $this->experience()->toArray();
        $data['pcd_type_id'] = $this->pcd_typo();
        $data['report'] = $this->report()->toArray();
        $data['cellphone'] = $this->phone();
        $data['english_level'] = $this->english_level_obj().'';
        $data['state_id'] = $this->state().'';
        $data['city_id'] = $this->city().'';
        $data['payment'] = $this->payment_formatted().'';

        
        
        
        
        return $data;
    }

}
