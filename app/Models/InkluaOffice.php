<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class InkluaOffice
 *
 * @property $id
 * @property $name
 * @property $leader_id
 * @property $created_at
 * @property $updated_at
 *
 * @property InkluaUser[] $inkluaUsers
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class InkluaOffice extends Model {

    protected $table = 'inklua_office';
    static $rules = [
        'name' => 'required',
        'leader_id' => 'nullable',
    ];
    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'leader_id', 'pfl_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inkluaUsers() {
        return InkluaUser::where('office_id',$this->id)->orderBy('active', 'DESC')->orderBy('updated_at', 'DESC');
    }

    public function inkluaUsersActive() {
        return InkluaUser::select('user_id')->where('office_id',$this->id)->where('active',1);
        
    }

    public function inkluaUsersContent() {        
        return Content::
                where('type',1)
                ->whereIN('user_id', $this->inkluaUsers()->get()->pluck('user_id') );
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user() {
        return $this->hasOne('App\Models\User', 'id', 'leader_id');
    }

    public function user_pfl() {
        return $this->hasOne('App\Models\User', 'id', 'pfl_id');
    }

    public function __toString() {
        return ucfirst($this->name);
    }

    public function delete() {
        $this->active = 0;
        foreach ($this->inkluaUsersActive()->get() as $ink) {
            $ink->user()->revoke();
        }
        $this->save();
    }

}
