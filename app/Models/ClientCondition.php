<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
class ClientCondition extends Model {

    
    use HasFactory;
    
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
    protected $fillable = ['condition_id', 'client_id', 'brute', 'tax', 'guarantee', 'start_cond', 'end_cond', 'active'];

       public static function boot()
    {
       parent::boot();
       static::creating(function($model)
       {
           $user = Auth::user();
           if($user == null)    $user = auth()->guard('api')->user();
           if($user != null) {
           $model->created_by = $user->id;
           $model->updated_by = $user->id;
           }
       });
       static::updating(function($model)
       {
           $user = Auth::user();
           if($user == null)    $user = auth()->guard('api')->user();
           if($user != null) 
           $model->updated_by = $user->id;
       });
   }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function client() {
        return $this->hasOne('App\Models\Client', 'id', 'client_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function condition() {
        return $this->hasOne('App\Models\Condition', 'id', 'condition_id');
    }

    public function toArray() {
        $data = parent::toArray();
        $data['condition_id'] = $this->condition()->first();
        return $data;
    }

    public function __toString() {
        $cond = $this->condition()->first();
        $string = $cond->name;
        if ($cond->intervals) {
            if ($cond->money) {
                $string .= 'R$';
            }


            $string .= $this->start_cond . ' -';
            if ($cond->money) {
                $string .= 'R$';
            }
            $string .= $this->end_cond;
        }
        return $string;
    }

}
