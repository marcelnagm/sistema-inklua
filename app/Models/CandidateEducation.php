<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

/**
 *  Education  Parte do sistema hunting
 *
 * @author marcel
 */
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Support\Str;
use Carbon;

class CandidateEducation extends Model {

    protected $table = 'candidate_education_hunting';
    protected $fillable = [
        'candidate_id',
        'level_education_id',
        'institute',
        'course',
        'start_at',
        'end_at'
    ];
    static $rules = array(
        'level_education_id' => 'required|max:255',
        'institute' => 'required|max:255',
        'course' => 'required|max:255',
        'start_at' => 'required|max:255',
        'end_at' => 'nullable'
    );
    protected $dates = [
        'created_at',
        'updated_at',
        'start_at',
        'end_at'
    ];
    
    protected $casts = [
        'start_at' => 'date',
        'end_at' => 'date',
    ];

     public function __construct($param = null) {

//             dd($param);
        if ($param != null) {
            if (isset($param['start_at'])) {
                $this->start_at = Carbon\Carbon::createFromFormat('d/m/Y', $param['start_at']);
                if (isset($param['end_at'])) {
                    $this->end_at = Carbon\Carbon::createFromFormat('d/m/Y', $param['end_at']);
                }
                unset($param['start_at'], $param['end_at']);
            }
            parent::__construct($param);
        }
    }

    public function level_education() {
        return LevelEducation::find($this->level_education_id);
    }

    public function toArray() {
        $data = parent::toArray();
        unset($data['level_education_id'] );
        $data['level_education'] = $this->level_education() . "";
        return $data;
    }

    
     public function update(array $attributes = [], array $options = []) {
//        dd($attributes);
        if (isset($attributes['start_at'])) {
            $attributes['start_at'] = Carbon\Carbon::createFromFormat('d/m/Y',$attributes['start_at']);
        }
        if (isset($attributes['end_at'])) {
           $attributes['end_at']= Carbon\Carbon::createFromFormat('d/m/Y',  $attributes['end_at']);
        }
//        unset($param['start_at'], $param['end_at']);
        parent::update($attributes, $options);
    }
}
