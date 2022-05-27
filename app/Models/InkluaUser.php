<?php

namespace App\Models;
//Parte do sistema hunting


use Illuminate\Database\Eloquent\Model;
use App\Models\InkluaOffice;

class InkluaUser extends Model
{
    //
    protected $table = 'inklua_users';
    protected $fillable = [ 'user_id','active','start_at','end_at','office_id'
        ];
    
        public function office() {
            return InkluaOffice::find($this->office_id);
        }
    
    
static function  isInternal($id){
    return InkluaUser::where('user_id',$id)->count()==1;
    
}


    
}
