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
class InkluaOffice extends Model
{
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
    protected $fillable = ['name','leader_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inkluaUsers()
    {
        return $this->hasMany('App\Models\InkluaUser', 'office_id', 'id')->orderBy('active','DESC')->orderBy('updated_at','DESC');
    }
    
    public function inkluaUsersActive(){
        return $this->hasMany('App\Models\InkluaUser', 'office_id', 'id')->where('active','1');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'leader_id');
    }
    

        public function __toString() {
        return ucfirst($this->name);
    }

    
}
