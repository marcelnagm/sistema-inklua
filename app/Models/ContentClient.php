<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
		'content_id' => 'required',
		'vacancy' => 'required',		
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['content_id','client_condition_id','client_id','vacancy'];


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
    public function clientcondition()
    {
        return $this->hasOne('App\Models\ClientCondition', 'id', 'client_condition_id');
    }
    
    

}
