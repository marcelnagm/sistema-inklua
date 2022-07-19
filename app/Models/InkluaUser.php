<?php

namespace App\Models;

//Parte do sistema hunting


use Illuminate\Database\Eloquent\Model;
use App\Models\InkluaOffice;
use App\Models\User;
use App\Models\OfficeRole;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InkluaUser extends Model {

    //
    protected $table = 'inklua_users';
    protected $fillable = ['user_id', 'active', 'start_at', 'end_at', 'office_id', 'role_id'
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'start_at',
        'end_at'
    ];

    public static function boot() {
        parent::boot();
        static::creating(function ($model) {
            $user = Auth::user();
            if ($user == null)
                $user = auth()->guard('api')->user();
            $model->created_by = $user->id;
            $model->updated_by = $user->id;
        });
        static::updating(function ($model) {
            $user = Auth::user();
            if ($user == null)
                $user = auth()->guard('api')->user();
            $model->updated_by = $user->id;
        });
    }

    public function office() {
        return InkluaOffice::find($this->office_id);
    }

    public function role() {
        return OfficeRole::find($this->role_id);
    }

    public function user() {
        return User::find($this->user_id);
    }

    public function save(array $options = []) {
        if (in_array($this->role_id, array(1, 2))) {
            $of = $this->office();
            if ($this->role_id == 1)
                $of->leader_id = $this->user()->id;
            if ($this->role_id == 2)
                $of->pfl_id = $this->user()->id;
            $of->save();
        }

        parent::save($options);
    }

    public static function inkluaUsers() {
        return InkluaUser::orderBy('active', 'DESC')->orderBy('updated_at', 'DESC');
    }

    public function positions() {
        return Content::where('user_id', $this->user_id);
    }

    public function positionsTotal() {
        return $this->positions();
    }
    
    public function positionsSum($positions) {
        
        return $positions->each(function($item,$key){
            $item['carteira'] = $item->carteira();
            return $item;
        })->sum('carteira');  
        
    
        
        }
    public function positionsClosedSum() {
        
        return $this->positionsClosed()->each(function($item,$key){
            $item['carteira'] = $item->carteira();
            return $item;
        })->sum('carteira');  
        
    }
    
    public function positionsClosed() {
        return $this->positions()->where('contents.status','fechada');
    }
    
    
    
    public function positionsWithClient() {
        return $this->positions()->
                whereRaw('contents.id in (select job_id from candidate_report where report_status_id = 8 )');
                
    }

     public function filters(Request $request, $vagas) {

        if ($request->exists('content_id')) {
            $vagas = $vagas->where('contents.id', '=', $request->input('content_id'));
        } else {
            if ($request->exists('date_start') && $request->exists('date_end')) {
                $date_start = Carbon\Carbon::createFromFormat('d/m/Y', $request->input('date_start'))->format('Y/m/d');
                $date_end = Carbon\Carbon::createFromFormat('d/m/Y', $request->input('date_end'))->format('Y/m/d');
                $vagas = $vagas->whereRaw('(contents.created_at between "' . $date_start
                        . '" and '
                        . '"' . $date_end . '"'
                        . ' or (status="publicada" and  contents.created_at  <= "' . $date_start . '")'
                        . ')');
            }
            if ($request->exists('title')) {
                $vagas = $vagas->where('contents.title', 'like', '%' . $request->input('title') . '%');
            }
            if ($request->exists('client')) {
                $vagas = $vagas->whereRaw('contents.id in (select content_id as id from contents_client,clients where contents_client.client_id = clients.id and clients.formal_name like ? )', '%' . $request->input('client') . '%');
            }
            if ($request->exists('recruiter')) {
                $vagas = $vagas->whereRaw('contents.user_id in (select id as id from users where users.name like ?  or users.lastname like ?)', array('%' . $request->input('client') . '%', '%' . $request->input('client') . '%'));
            }
            if ($request->exists('office')) {
                $vagas = $vagas->whereRaw('contents.user_id in (select user_id as id from inklua_users where office_id = ?)', array($request->input('office')));
            }
            if ($request->exists('key')) {
                $vagas = $vagas->whereRaw('(contents.user_id in (select user_id as id from inklua_users where office_id = ?) or '
                        . 'contents.id in (select content_id as id from contents_client,clients where contents_client.client_id = clients.id and clients.formal_name like ? ) or '
                        . 'contents.title like ? or '
                        . 'contents.id = ?  or  '
                        . 'contents.user_id in (select user_id from inklua_users where user_id in (select id from users where name like ?))'
                        . ')',
                        array($request->input('key'),
                            '%' . $request->input('key') . '%', '%' . $request->input('key') . '%',
//                            '%' . $request->input('key') . '%',
                            $request->input('key'),
                            '%' . $request->input('key') . '%'
                        )
                );
            }
            if ($request->exists('status')) {
                $vagas = $vagas->where('contents.status', $request->input('status'));
            }
        }
        if ($request->exists('debug')) {
            dd(Controller::getEloquentSqlWithBindings($vagas));
        }



        return $vagas;
    }
    
}
