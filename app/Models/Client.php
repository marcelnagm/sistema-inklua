<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ClientCondition;

/**
 * Class Client
 *
 * @property $id
 * @property $cnpj
 * @property $formal_name
 * @property $fantasy_name
 * @property $phone
 * @property $sector
 * @property $local_label
 * @property $active
 * @property $state_id
 * @property $created_at
 * @property $updated_at
 *
 * @property State $state
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Client extends Model
{
    
    static $rules = [
		'cnpj' => 'required',
		'formal_name' => 'required',
		'fantasy_name' => 'required',
		'sector' => 'required',
		'local_label' => 'required',
		'active' => 'required',
		'state_id' => 'required',
		'obs' => 'nullable',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['cnpj','formal_name','fantasy_name','sector','local_label','active','state_id', 'obs'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function state()
    {
        return $this->hasOne('App\Models\State', 'id', 'state_id');
    }
    
    public function conditions()
    {
      return ClientCondition::where('client_id', $this->id)->
              where('active',1)
              ->get();
    }
    

}
