<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    use HasFactory;


    protected $fillable = [
        'wallet_id',
        'content_id',
        'action',
        'media',
        'coins'
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'wallet_id',
        'content_id',
    ];

    public function wallet()
    {
        return $this->belongsTo(\App\Models\Wallet::class);
    }

    public function content()
    {
        return $this->belongsTo(\App\Models\Content::class);
    }
}
