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
use App\Models\InkluaUser;
use Carbon;
use App\Models\InkluaOffice;

class User extends Authenticatable implements MustVerifyEmail {

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
    protected $fillable = ['id',
        'cnpj',
        'fantasy_name',
        'name',
        'lastname',
        'phone',
        'email',
        'password',
        'accepted_terms',
        'facebook_id',
        'google_id',
        'type',
        'has_password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
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
    static $rules = array(
        'cnpj' => 'required|max:255',
        'fantasy_name' => 'required|max:255',
        'name' => 'required|max:255',
        'lastname' => 'required|max:255',
        'phone' => 'required|max:255',
        'email' => 'required|max:255',
    );

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

    public function wallet() {
        return $this->hasOne(Wallet::class);
    }

    public function fullname() {
    return $this->name.' '.$this->lastname;    
    }
    
    public function contents() {
        return $this->hasMany(Content::class);
    }

    public function notifications() {
        return $this->hasMany(Notification::class);
    }

    public function sendPasswordResetNotification($token) {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function sendEmailVerificationNotification() {
        $this->notify(new VerifyEmail);
    }

    public function getWallet() {
        if (!$this->wallet()->exists()) {
            $this->createWallet();
        }

        return $this->wallet;
    }

    public function createWallet() {
        return \App\Models\Wallet::create(["user_id" => $this->id]);
    }

    public function deleteAccount() {
        if (!$this->admin == 1) {
            $this->delete();
        }
    }

    protected static function booted() {
        static::deleting(function ($user) {
            $wallet = $user->wallet()->delete();

            $contents = $user->contents()->get();
            foreach ($contents as $content) {
                $content->delete();
            }

            $notifications = $user->notifications()->get();
            foreach ($notifications as $notification) {
                $notification->delete();
            }
        });
    }

    public function getMyContents($search = FALSE, $status = FALSE) {

        $searchEscaped = addslashes($search);

        $content = Content::selectRaw("id, type, image, title, group_id, date, description,city as 'cidade', state as 'estado', status, url, source,district,benefits, requirements, hours, english_level ,observation,created_at 
        ")
                ->selectRaw("(
                                (match (title) against ('{$searchEscaped}' in boolean mode) * 10)
                                + match (description) against ('{$searchEscaped}' in boolean mode)
                                - (ABS(DATEDIFF(`date`, NOW())) / 10)
                            ) as score")
                ->where("type", Content::getType('position'))
                ->where('user_id', $this->id)

                // Filtro apenas por query
                ->when(($search != ''), function ($query) use ($search) {
                    return $query->whereRaw('match (title, description) against (? in boolean mode)', [$search]);
                })

                //Filtro por status
                ->when($status, function ($query) use ($status) {
                    return $query->where('status', $status);
                })
                ->orderBy('score', 'desc')
                ->orderBy('id', 'desc')
                ->orderBy('ordenation')
                ->paginate(12);

        $content->data = Content::hideFields($content);
        return $content;
    }

    public function checkExistenceOfPositionByCnpj() {

        return Content::whereHas('user', function ($q) {
                    $q->where('users.cnpj', $this->cnpj);
                })->exists();
    }

    public function inklua() {
        return InkluaUser::
                        where('user_id', $this->id)
                        ->where('active', 1)->first();
    }
    public function office() {
        return $this->inklua()->office();
    }
    

    public function isInkluaLider() {
        return InkluaUser::
                        where('user_id', $this->id)->
                        whereIn('role_id', array(1,2))
                        ->where('active', 1)->count() == 1;
    }
    
    public function isInklua() {
        return InkluaUser::
                        where('user_id', $this->id)
                        ->where('active', 1)->count() == 1;
    }

    public function revoke() {
        $ink = InkluaUser::where('user_id', $this->id)
                        ->where('active', 1)->first();
//    dd($ink);
        $ink->active = 0;
        $ink->end_at = \Illuminate\Support\Carbon::now();
        $ink->save();
    }

    public function promote($request) {
        $data = $request->all();
        $data = array_merge($data, array(
                'user_id' => $this->id,
                'active' => 1,
                'start_at' => \Illuminate\Support\Carbon::now()
                ));
        $ink = new InkluaUser($data);
        $ink->save();
        return $this;
    }

    public function toArray() {
        $data = parent::toArray();
        $data['inkluer'] = $this->isInklua();
        $data['inkluer_leader'] = $this->isInkluaLider();
        return $data;
    }
    
    
}
