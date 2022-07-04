<?php

namespace App\Models;

//Parte do sistema hunting


use Illuminate\Database\Eloquent\Model;
use App\Models\InkluaOffice;
use App\Models\User;
use App\Models\OfficeRole;
use Illuminate\Support\Facades\Auth;

class InkluaUser extends Model {

    //
    protected $table = 'inklua_users';
    protected $fillable = ['user_id', 'active', 'start_at', 'end_at', 'office_id', 'role_id'
    ];
    
protected $dates = [
        'created_at',
        'updated_at',
        'start_at',
        'end_at'
    ];
 public static function boot()
    {
       parent::boot();
       static::creating(function($model)
       {
           $user = Auth::user();
           $model->created_by = $user->id;
           $model->updated_by = $user->id;
       });
       static::updating(function($model)
       {
           $user = Auth::user();
           $model->updated_by = $user->id;
       });
   }

    public function office() {
        return InkluaOffice::find($this->office_id);
    }
    public function role() {
        return OfficeRole::find($this->role_id);
    }

    public function user() {
        return User::find($this->user_id);
    }

    public function save(array $options = []) {
        if (in_array($this->role_id , array(1,2))) {
            $of = $this->office();
            if ($this->role_id == 1) $of->leader_id =$this->user()->id ;
            if ($this->role_id == 2) $of->pfl_id =$this->user()->id ;
            $of->save();
        }

        parent::save($options);
    }

    public static function inkluaUsers() {
        return InkluaUser::orderBy('active', 'DESC')->orderBy('updated_at', 'DESC');
    }
    
    static function isInternal($id) {
        return InkluaUser::where('user_id', $id)->count() == 1;
    }

}
