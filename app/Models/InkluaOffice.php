<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon;
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
class InkluaOffice extends Model {

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
    protected $fillable = ['name', 'leader_id', 'pfl_id'];

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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inkluaUsers() {
        return InkluaUser::where('office_id',$this->id)->orderBy('active', 'DESC')->orderBy('updated_at', 'DESC');
    }

    public function inkluaUsersActive() {
        return InkluaUser::select('user_id')->where('office_id',$this->id)->where('active',1);
        
    }

    public function inkluaUsersContent(Request $request) {        
            $date_start = Carbon\Carbon::createFromFormat('d/m/Y', $request->input('date_start'))->format('Y/m/d');
            $date_end = Carbon\Carbon::createFromFormat('d/m/Y', $request->input('date_end'))->format('Y/m/d');
            
        return Content::
                where('type',1)
                ->whereRaw('contents.user_id in (SELECT user_id FROM `inklua_users` WHERE ((`start_at` >= ? and `end_at` <= ?) or (`start_at` >= ? and active=1) ) and office_id = ?)',
                        array(
                            $date_start,$date_end,
                            $date_start, $this->id
                        ));
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user() {
        return $this->hasOne('App\Models\User', 'id', 'leader_id');
    }

    public function user_pfl() {
        return $this->hasOne('App\Models\User', 'id', 'pfl_id');
    }

    public function __toString() {
        return ucfirst($this->name);
    }

    public function delete() {
        $this->active = 0;
        foreach ($this->inkluaUsersActive()->get() as $ink) {
            $ink->user()->revoke();
        }
        $this->save();
    }

}
