<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Models\ClientCondition;
use Illuminate\Support\Facades\Auth;

/**
 * Class ContentClient
 *
 * 
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ContentClient extends Model
{
     protected $table = 'contents_client';
    
    static $rules = [
		'client_condition_id' => 'required',
		'client_id' => 'required',	
		'vacancy' => 'required',		
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['content_id','client_condition_id','client_id','user_id','vacancy','hired','replacement'];
    
     public static function boot()
    {
       parent::boot();
       static::creating(function($model)
       {
            $user = auth()->guard('api')->user();
           $model->created_by = $user->id;
           $model->updated_by = $user->id;
       });
       static::updating(function($model)
       {
            $user = auth()->guard('api')->user();
           $model->updated_by = $user->id;
       });
   }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function client()
    {
        return $this->hasOne('App\Models\Client', 'id', 'client_id');
    }
    
      public function user() {
        return User::find($this->user_id);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function clientcondition()
    {
        return $this->hasOne('App\Models\ClientCondition', 'id', 'client_condition_id');
    }
    
    public function hasVacancy(){
        return $this->vacancy > ($this->hired - $this->replaced);
    }

}
