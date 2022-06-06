<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ClientCondition
 *
 * @property $id
 * @property $condition_id
 * @property $client_id
 * @property $brute
 * @property $tax
 * @property $guarantee
 * @property $start_cond
 * @property $end_cond
 * @property $created_at
 * @property $updated_at
 *
 * @property Client $client
 * @property Condition $condition
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ClientCondition extends Model
{
     protected $table = 'client_condition';
    
    static $rules = [
		'condition_id' => 'required',
		'client_id' => 'required',
		'brute' => 'required',
		'tax' => 'required',
		'guarantee' => 'required',
		'active' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['condition_id','client_id','brute','tax','guarantee','start_cond','end_cond','active'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function client()
    {
        return $this->hasOne('App\Models\Client', 'id', 'client_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function condition()
    {
        return $this->hasOne('App\Models\Condition', 'id', 'condition_id');
    }
    
    
    public function toArray() {
        $data = parent::toArray();
        $data['condition_id'] = $this->condition()->first();
        return $data;
    }

}
