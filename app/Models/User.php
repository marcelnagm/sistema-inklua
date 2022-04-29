<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Passport\HasApiTokens;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'lastname',
        'email',
        'password',
        'has_password',
        'accepted_terms',
        'facebook_id',
        'google_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
        'admin',
        'current_team_id',
        'profile_photo_path',
        'profile_photo_url',
        'facebook_id',
        'google_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function wallet()
    {
        return $this->hasOne(\App\Models\Wallet::class);
    }


    public function sendPasswordResetNotification($token)
    {
        $this->notify( new ResetPasswordNotification($token) );
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    public function getWallet()
    {
        if(!$this->wallet()->exists()){
            $this->createWallet();
        }

        return $this->wallet;
    }

    public function createWallet()
    {
        return \App\Models\Wallet::create(["user_id" => $this->id]);
    }

    public function deleteAccount(){
        if(!$this->admin == 1){
            $this->delete();
        }
    }

    protected static function booted()
    {
        static::deleting(function ($user) {
            $wallet = $user->wallet()->first();
            if($wallet)
                $wallet->delete();
        });
    }
}
