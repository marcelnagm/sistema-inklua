<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id'
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'user_id'
    ];

    public function actions()
    {
        return $this->hasMany(\App\Models\Action::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    protected static function booted()
    {
        static::deleting(function ($wallet) {
            $wallet->actions()->delete();
        });
    }

}
