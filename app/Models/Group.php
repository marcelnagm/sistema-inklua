<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Content;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'title'
    ];

    public function contents(){
        return $this->hasMany('App\Models\Content', 'group_id');
    }
}
