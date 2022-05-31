<?php

namespace App\Models;

//Parte do sistema hunting


use Illuminate\Database\Eloquent\Model;
use App\Models\InkluaOffice;
use App\Models\User;

class InkluaUser extends Model {

    //
    protected $table = 'inklua_users';
    protected $fillable = ['user_id', 'active', 'start_at', 'end_at', 'office_id', 'role_id'
    ];

    public function office() {
        return InkluaOffice::find($this->office_id);
    }

    public function user() {
        return User::find($this->user_id);
    }

    public function save(array $options = []) {
        if ($this->role_id == 1) {
            $of = $this->office();
            $of->leader_id = $this->end_at == null ? $this->user()->id : null;
            $of->save();
        }

        parent::save($options);
    }

    static function isInternal($id) {
        return InkluaUser::where('user_id', $id)->count() == 1;
    }

}
