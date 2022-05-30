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
        return $this->hasMany('App\InkluaUser', 'office_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\User', 'id', 'leader_id');
    }
    

        public function __toString() {
        return ucfirst($this->name);
    }

    
}
