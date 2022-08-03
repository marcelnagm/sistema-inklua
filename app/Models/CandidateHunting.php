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
use App\Models\CandidateGender;
use App\Models\CandidateRace;
use Carbon;
use \Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class CandidateHunting extends Model {

       use HasFactory;
       
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
        'status',
        'pcd_type_id',
        'pcd_details',
        'pcd_report',
        'first_job',
        'state_id',
        'city_id',
        'remote', 'move_out'
        , 'race_id', 'gender_id',
        'english_level'
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'birth_date'
    ];
    protected $casts = [
        'start_at' => 'date',
        'end_at' => 'date',
        'birth_date' => 'date',
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
        'status' => 'nullable',
        'linkedin_url' => 'nullable',
        'pcd' => 'required|max:1',
        'first_job' => 'required|max:1',
        'cv_path' => 'nullable',
        'pcd_type_id' => 'nullable',
        'pcd_details' => 'nullable',
        'pcd_report' => 'nullable',
        'state_id' => 'required|max:255',
        'city_id' => 'required|max:255',
        'english_level' => 'required|max:1',
        'remote' => 'required|max:1',
        'move_out' => 'required|max:1',
        'race_id' => 'required|max:2',
        'gender_id' => 'required|max:2'
    );

//    protected $hidden = [ ‘password’ ];

    public function __construct($param = null) {
        if ($param != null) {
            if (isset($param['birth_date'])) {
                $this->birth_date = Carbon\Carbon::createFromFormat('d/m/Y', $param['birth_date']);
//                $this->cellphone = str_replace(['(',')','-'],'' ,$param['cellphone']);                
                unset($param['birth_date']);
                $this->generate();
            }
            parent::__construct($param);
        }
    }

    public function generate() {
        $this->gid = md5(random_int(1, 125) * time() . Str::random(20));
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

    public function age() {
        return \Carbon\Carbon::parse($this->birth_date)->age;
    }

    public function full_name() {
        return $this->name . ' ' . $this->surname;
    }

    public function payment_formatted() {
        return number_format($this->payment, 2, ',', '.');
    }

    public function status_name() {
        switch ($this->status) {
            case -1:
                return 'BLOCKED';
            case 0:
                return "AVAILABLE";
            default: {
                    if ($this->status == auth()->guard('api')->user()->id)
                        return 'YOURS';
                    else
                        return "TAKEN";
                }
        }
    }

    public function state() {
        return State::find($this->state_id);
    }

    public function city() {
        return City::find($this->city_id);
    }

    public function pcd_typo() {
        return $this->pcd_type_id != null ? PcdType::find($this->pcd_type_id) : "Nenhum";
    }

    /**
     * 
     * @return \Illuminate\Support\Collection;
     */
    public function education() {
        return CandidateEducation::where('candidate_id', $this->id)->orderBy('level_education_id')->get();
    }

    public function education_max() {
        return CandidateEducation::where('candidate_id', $this->id)->orderBy('level_education_id')->get()->pluck('level_education_id')->max();
    }

    /**
     * 
     * @return \Illuminate\Support\Collection;
     */
    public function report($job_id = null) {
        if($job_id == null)
        return CandidateReport::where('candidate_id', $this->id)->orderBy('updated_at', 'DESC')->get();
        else return CandidateReport::where('candidate_id', $this->id)->where('job_id', $job_id)->first();
    }

    /**
     * 
     * @return \Illuminate\Support\Collection;
     */
    public function experience() {
        return CandidateExperience::where('candidate_id', $this->id)->orderBy('end_at', 'ASC')->get();
    }

    /**
     * 
     * @return CandidateExperience
     */
    public function last_experience() {
        $last =CandidateExperience::where('candidate_id', $this->id)->orderBy('end_at', 'ASC')->get()->last(); 
        return $last != null ? $last->role : 'Nenhum';
    }

    public function save_pcd_report($pcd_report, $ext) {

        if ($pcd_report != null) {
            if (Storage::exists("docs/$this->gid"))
                Storage::makeDirectory("docs/$this->gid");

            Storage::disk('local')->put("docs/$this->gid/pcd_report.$ext", base64_decode($pcd_report));
            $this->pcd_report = "docs/$this->gid/pcd_report.$ext";
        }
    }

    public function save_cv_path($cv_path, $ext) {

        if ($cv_path != null) {
            if (Storage::exists("docs/$this->gid"))
                Storage::makeDirectory("docs/$this->gid");

            Storage::disk('local')->put("docs/$this->gid/cv.$ext", base64_decode($cv_path));
            $this->cv_path = "docs/$this->gid/cv.$ext";
        }
    }

    public function compact() {
        $data = $this->toArray();
        $data['age'] = $this->age();
        unset($data['state_id'], $data['city_id'], $data['pcd_type_id']);
        $data['state'] = $this->state() . '';
        $data['city'] = $this->city()->name . '';
        $data['payment'] = $this->payment_formatted() . '';
        $data['pcd_type'] = $this->pcd_typo();
        $data['cellphone'] = $this->phone();
        $data['english_level'] = $this->english_level_obj() . '';
        $data['gid'] = $this->gid;
        $data['recruitment']['status'] = $this->status_name();
        if ($data['recruitment']['status'] == "TAKEN") {
            $data['recruitment']['recruiterName'] = User::find($this->status)->fullname();
        }
        $data['education'] = $this->education()->count() != 0 ?$this->education()->toArray() : 'Nenhum';
        $data['experience'] = $this->experience()->count() != 0 ? $this->experience()->toArray() : 'Nenhum';
        $data['report'] = $this->report()->toArray();

        return $data;
    }

    public function gender() {
        return CandidateGender::find($this->gender_id);
    }

    public function race() {
        return CandidateRace::find($this->race_id);
    }

    public function save(array $attributes = [], array $options = []) {

        if (isset($attributes['payment'])) {
            if (str_contains($attributes['payment'], '.'))
                $attributes['payment'] = $attributes['payment'] * 1000;
        }
        if (isset($attributes['birth_date'])) {
            $attributes['birth_date'] = Carbon\Carbon::createFromFormat('d/m/Y', $attributes['birth_date']);
        }
        parent::save($attributes, $options);
    }

    public function update(array $attributes = [], array $options = []) {
//        dd($attributes);
        if (isset($attributes['birth_date'])) {
            $attributes['birth_date'] = Carbon\Carbon::createFromFormat('d/m/Y', $attributes['birth_date']);
        }
        if (isset($attributes['payment'])) {
            if (str_contains($attributes['payment'], '.'))
                $attributes['payment'] = $attributes['payment'] * 1000;
        }

        parent::update($attributes, $options);
    }

    static function byStatus($status) {
        $sub = CandidateReport::whereIn('report_status_id', $status)->pluck('candidate_id');
        return CandidateHunting::whereIn('id', $sub);
    }

    public function toArray() {
        $data = parent::toArray();
        $data['age'] = $this->age();
        return $data;
    }

}
