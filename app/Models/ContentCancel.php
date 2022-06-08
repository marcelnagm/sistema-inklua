<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Models\User;

/**
 * Class ContentClient
 *
 * 
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ContentCancel extends Model
{
     protected $table = 'content_canceled';
    
    static $rules = [
		'user_id' => 'required',
		'client_id' => 'required',
		'content_id' => 'required',
		'reason' => 'required',		
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['content_id','user_id','client_id','reason'];


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
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
    
    

}
