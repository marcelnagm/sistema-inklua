<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

/**
 * Report Parte do sistema hunting
 *
 * @author marcel
 */
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Support\Str;
use App\Models\CandidateHunting as Candidate;
use Auth;
use Carbon;
use App\Models\ReportStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
       

class CandidateReport extends Model {

      use HasFactory;
       
    protected $table = 'candidate_report';
    protected $fillable = [
        'candidate_id',
        'job_id',
        'hired',
        'start_at',
        'owner',
        'obs',
        'report_status_id',
        'user_id'
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'start_at'
    ];
    protected $casts = [
        'start_at' => 'date'
    ];
    static $rules = array(
        'candidate_id' => 'nullable',
        'job_id' => 'nullable',
        'hired' => 'nullable',
        'owner' => 'nullable',
        'start_at' => 'nullable',
        'obs' => 'nullable',
        'report_status_id' => 'nullable'
    );
    static $rules_hired = array(
        'hired' => 'required',
        'owner' => 'nullable',
        'start_at' => 'required',
        'obs' => 'nullable',
        'report_status_id' => 'required'
    );

    public function __construct($param = null) {
        if ($param != null) {
            if (isset($param['start_at'])) {
                $this->start_at = Carbon\Carbon::createFromFormat('d/m/Y', $param['start_at']);
                unset($param['start_at']);
            }
            parent::__construct($param);
        }
    }

    public static function boot() {
        parent::boot();
        static::creating(function ($model) {
            $user = auth()->guard('api')->user();
               if ($user != null){
            $model->created_by = $user->id;
            $model->user_id = $user->id;
            $model->updated_by = $user->id;
               }
        });
        static::updating(function ($model) {
            $user = auth()->guard('api')->user();
            if ($user != null){
            $model->updated_by = $user->id;
            }
        });
    }

    public function save(array $attributes = [], array $options = []) {

//        dd($attributes);
        if (isset($attributes['start_at'])) {
            $attributes['start_at'] = Carbon\Carbon::createFromFormat('d/m/Y', $attributes['start_at']);
        }
        if (isset($attributes['report_status_id'])) {
            if ($attributes['report_status_id'] == 5) {
                $candidate = $this->candidate();
                $candidate->status = -1;
                $candidate->save();
            } else {
                if ($attributes['report_status_id'] == 6 || $attributes['report_status_id'] == 7) {
                    $cont = $this->content()->contentclient();
                    if ($attributes['report_status_id'] == 6) {
                        $attributes['hired'] = 1;
                        parent::save($attributes, $options);
                        $candidate = $this->candidate();
                        $candidate->status = -9999;
                        $candidate->save();
                        $cont->hired = $cont->hired + 1;
                        $cont->save();
                    } else {
                        if ($attributes['report_status_id'] == 7) {
                            $candidate = $this->candidate();
                            if ($candidate->status != -9999) {
                                return response()->json([
                                            'status' => true,
                                            'msg' => 'Você não pode fazer a reposição de um candidato não contratado!',
                                ]);
                            }

                            $this->replacement();
                            parent::save($attributes, $options);

                            return response()->json([
                                        'status' => true,
                                        'msg' => 'Candidato Reposto!',
                            ]);
                        }
                    }
                } else {
                    $candidate = $this->candidate();
//                    dd($this );
                    $candidate->status = NULL;
                    $candidate->save();
                }
            }
        }
        parent::save($attributes, $options);
        return $this;
    }

    public function update(array $attributes = [], array $options = []) {
//        dd($attributes);        
        if (isset($attributes['start_at'])) {
            $attributes['start_at'] = Carbon\Carbon::createFromFormat('d/m/Y', $attributes['start_at']);
        }
        if (isset($attributes['report_status_id'])) {
            if ($attributes['report_status_id'] == 5) {
                $candidate = $this->candidate();
                $candidate->status = -1;
                $candidate->save();
                parent::update($attributes, $options);
            } else {
                if ($attributes['report_status_id'] == 6 || $attributes['report_status_id'] == 7) {
                    $cont = $this->content()->contentclient();
                    if ($attributes['report_status_id'] == 6) {
                        $attributes['hired'] = 1;
                        $candidate = $this->candidate();
                        $candidate->status = -9999;
                        $candidate->save();
                        $cont->hired = $cont->hired + 1;
                        $cont->save();
                        parent::update($attributes, $options);
                    } else {
                        if ($attributes['report_status_id'] == 7) {
                            $candidate = $this->candidate();
                            if ($candidate->status != -9999) {
                                return response()->json([
                                            'status' => true,
                                            'msg' => 'Você não pode fazer a reposição de um candidato não contratado!',
                                ]);
                            }

                            $this->replacement();
                            parent::update($attributes, $options);

                            return response()->json([
                                        'status' => true,
                                        'msg' => 'Candidato Reposto!',
                            ]);
                        }
                    }
                } else {
//                    parent::update($attributes, $options);
                    $candidate = $this->candidate();
                    $candidate->status = NULL;
                    $candidate->save();
                }
            }
        }
//         parent::update($attributes, $options);
        return $this;
    }

    public function user() {
        return User::find($this->user_id);
    }

    public function owner_obj() {
        return User::find($this->owner);
    }

    public function owner_formatted() {
        if ($this->owner != null)
            return $this->owner_obj()->fullname();
        else {
            if ($this->user_id != null)
                return $this->user()->fullname();
            else
                return '-';
        }
    }

    /**
     * 
     * @return CandidateHunting
     */
    public function candidate() {
        return Candidate::find($this->candidate_id);
    }

    public function content() {
        return Content::find($this->job_id);
    }

    public function replacement() {
        $candidate = $this->candidate();
        $cont = $this->content()->contentclient();
        $candidate->status = null;
        $candidate->save();
        $cont->replaced = $cont->replaced + 1;
        $cont->save();
        $content = $this->content();
        $content->status = 'reposicao';
        $content->save();
        $this->hired = 0;
        $this->report_status_id = 7;
        $this->save();
    }

    public function reportstatus() {
        return ReportStatus::find($this->report_status_id);
    }

    public function toArray() {
        $data = array();
        $data['recruiter'] = $this->owner_formatted();
        $data['date'] = $this->created_at;
        $data['candidate_id'] = $this->candidate_id;
        $data['hired'] = $this->hired;
        if ($this->report_status_id != null)
            $data['report_status'] = ReportStatus::find($this->report_status_id)->status_front;
        $data['obs'] = $this->obs;
        $data['id'] = $this->id;

        return $data;
    }

}
