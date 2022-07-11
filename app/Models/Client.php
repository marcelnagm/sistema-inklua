<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ClientCondition;
use Illuminate\Support\Facades\Auth;

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
    static $rules_create = [
		'cnpj' => 'required|unique:clients,cnpj',
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

     public static function boot()
    {
       parent::boot();
       static::creating(function($model)
       {
           $user = Auth::user();
           if($user == null)    $user = auth()->guard('api')->user();
           $model->created_by = $user->id;
           $model->updated_by = $user->id;
       });
       static::updating(function($model)
       {
           $user = Auth::user();
           if($user == null)    $user = auth()->guard('api')->user();
           $model->updated_by = $user->id;
       });
   }
    
    
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
    
    
    public function contents()
    {
      return ContentClient::select('content_id')->where('client_id', $this->id)              
              ->get();
    }
    
       public function __toString() {       
            return $this->formal_name . ' - '.$this->fantasy_name;
       
    }

}
