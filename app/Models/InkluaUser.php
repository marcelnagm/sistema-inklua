<?php

namespace App\Models;
//Parte do sistema hunting


use Illuminate\Database\Eloquent\Model;

class InkluaUser extends Model
{
    //
    protected $table = 'inklua_users';
    protected $fillable = [ 'user_id'
        ];
    
    
static function  isInternal($id){
    return InkluaUser::where('user_id',$id)->count()==1;
    
}
    
}
