<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OfficeRole
 *
 * @property $id
 * @property $role
 * @property $created_at
 * @property $updated_at
 *
 * @property InkluaUser[] $inkluaUsers
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class OfficeRole extends Model
{
      protected $table = 'office_role';
    static $rules = [
		'role' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['role'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inkluaUsers()
    {
        return $this->hasMany('App\InkluaUser', 'role_id', 'id');
    }
    

}
