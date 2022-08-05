<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Group;
use App\Models\ContentClient;
use App\Models\JobLike;
use App\Models\InkluaOffice;
use App\Models\CandidateReport;
use App\Models\ExternalLike;
use Illuminate\Support\Facades\Mail;
use App\Mail\PositionApproved;
use App\Mail\PositionPublished;
use App\Mail\PositionCreated;

class Content extends Model {

    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'contract_type',
        'salary',
        'image',
        'description',
        'application',
        'application_type',
        'remote',
        'status',
        'published_at',
        'observation',
        'url',
        'source',
        'type',
        'ordenation',
        'in_compleo',
        'compleo_code',
        'date',
        'city',
        'state',
        'cod_filial',
        'name_filial',
        'group_id',
        'category',
        'branch_code',
        'branch_name',
        'district',
        'benefits',
        'requirements',
        'hours',
        'english_level'
    ];
    protected $hidden = [
// 'id'
        'row_n',
        'order_type'
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'published_at'
    ];
    static $status = array(
        "aguardando_aprovacao",
        "aguardando_pagamento",
        "publicada",
        "reposicao"
        , "reprovada",
        "expirada",
        "fechada",
        "cancelada"
    );

    public static function boot() {
        parent::boot();
        static::creating(function ($model) {
            $user = Auth::user();
            if ($user == null)
                $user = auth()->guard('api')->user();
            if ($user != null) {

                $model->created_by = $user->id;
                $model->updated_by = $user->id;
                if ($user->office() != null)
                    $model->office_id = $user->office()->id;
            }
        });
        static::updating(function ($model) {
            $user = Auth::user();
            if ($user == null)
                $user = auth()->guard('api')->user();
            if ($user != null) {
                $model->updated_by = $user->id;
                if ($user->office() != null)
                    $model->office_id = $user->office()->id;
            }
        });
    }

    public function group() {
        return $this->belongsTo('App\Models\Group');
    }

    public function actions() {
        return $this->hasMany(\App\Models\Action::class);
    }

    public function candidateReport() {
        return CandidateReport::where('job_id', $this->id)->get();
    }

    /**
     * 
     * @return ContentClient
     */
    public function contentclient() {
        return ContentClient::where('content_id', $this->id)->first();
    }

    public static function getRandomImage() {
        $img = random_int(1, 16);
        return url("/img/{$img}.png");
    }

    /**
     * 
     * @return InkluaOffice
     */
    public function office() {
        if ($this->office_id != null)
            return InkluaOffice::find($this->office_id);
        else
            return '-';
    }

    public function company() {
        if ($this->user_id != null) {
            if ($this->user()->isInklua()) {
                return 'Inklua';
            } else {
                return $this->user()->fantasy_name;
            }
        }
    }

    static function companyName($user_id) {

        if ($user_id != null) {
            $user = User::find($user_id);

            if ($user != null) {
//                            dd($user);
                if ($user->isInklua()) {
                    return 'Inklua';
                } else {
                    return $user->fantasy_name;
                }
            }
        }
    }

    public static function getHomeContent() {
        $type = request()->input("tipo");
        $user = auth()->guard('api')->user();

        $content = Content::selectRaw("id, type, image, title, category, group_id, date, description,city as 'cidade', state as 'estado', url, source, created_at,user_id")
                ->selectRaw("(
                                CASE  
                                    WHEN type = 1 AND group_id IS NOT NULL THEN 1
                                    WHEN type = 1 THEN 2
                                    WHEN type = 3 THEN 3
                                    ELSE 4
                                END) AS order_type")
                ->selectRaw("row_number() OVER ( PARTITION BY order_type ORDER BY ordenation DESC, id DESC) row_n, IFNULL(group_id,UUID()) as unique_group")
                ->when($type, function ($query, $type) {
                    return $query->where("type", $type);
                })
                ->when($user && $user->wallet, function ($query) use ($user) {
                    return $query->with(['actions' => function ($query) use ($user) {
                            $query->where('wallet_id', $user->wallet->id);
                        }]);
                })
                ->groupBy('unique_group')
                ->orderBy('row_n')
                ->orderBy('order_type')
                ->orderBy('ordenation')
                ->paginate(12)
        ;

// $content->data = Content::hideFields($content);
        return $content;
    }

    public static function hideFields($data) {
        return $data->makeHidden(['ordenation', 'compleo_code', 'cod_filial', 'branch_code', 'branch_name', 'updated_at', 'row_n', 'in_compleo']);
    }

    public static function getCities($search = FALSE, $state = FALSE, $city = FALSE) {
        return Content::select("city as name", DB::raw('count(*) as positions'))
                        ->where("type", 1)
                        ->whereIn('status', array('publicada', 'reposicao'))
                        ->whereNotNull("city")
                        ->where("city", "!=", "")
                        ->when(($search), function ($query) use ($search) {
                            $query->whereRaw('match (title, description) against (? in boolean mode)', [$search]);
                        })
                        ->when(($city), function ($query) use ($city) {
                            $query->where('city', $city);
                        })
                        ->when(($state), function ($query) use ($state) {
                            $query->where('state', $state);
                        })
                        ->groupBy("city")
                        ->orderBy("city")
                        ->get();
    }

    public static function getStates($search = FALSE, $state = FALSE, $city = FALSE) {
        return Content::select("state as name", DB::raw('count(*) as positions'))
                        ->where("type", 1)
                        ->whereIn('status', array('publicada', 'reposicao'))
                        ->whereNotNull("state")
                        ->where("state", "!=", "")
                        ->when(($search), function ($query) use ($search) {
                            $query->whereRaw('match (title, description) against (? in boolean mode)', [$search]);
                        })
                        ->when(($state), function ($query) use ($state) {
                            $query->where('state', $state);
                        })
                        ->when(($city), function ($query) use ($city) {
                            $query->where('city', $city);
                        })
                        ->groupBy("state")
                        ->orderBy("state")
                        ->get();
    }

    public static function getHiringModel($search = FALSE, $state = FALSE, $city = FALSE, $contract = FALSE) {
        $counter = [
            [
                'name' => 'Remoto',
                'positions' => 0
            ],
            [
                'name' => 'Presencial',
                'positions' => 0
            ]
        ];

        if ($contract != 'presencial') {
            $remote = Content::where("type", 1)
                    ->whereIn('status', array('publicada', 'reposicao'))
                    ->where("city", "")
                    ->when(($search), function ($query) use ($search) {
                        $query->whereRaw('match (title, description) against (? in boolean mode)', [$search]);
                    })
                    ->when(($state), function ($query) use ($state) {
                        $query->where('state', $state);
                    })
                    ->when(($city), function ($query) use ($city) {
                $query->where('city', $city);
            });

            $counter[0]['positions'] = $remote->count();
        }


        if ($contract != 'remoto') {
            $inPerson = Content::where("type", 1)
                    ->whereIn('status', array('publicada', 'reposicao'))
                    ->whereNotNull("city")
                    ->where("state", "!=", "")
                    ->when(($search), function ($query) use ($search) {
                        $query->whereRaw('match (title, description) against (? in boolean mode)', [$search]);
                    })
                    ->when(($state), function ($query) use ($state) {
                        $query->where('state', $state);
                    })
                    ->when(($city), function ($query) use ($city) {
                $query->where('city', $city);
            });

            $counter[1]['positions'] = $inPerson->count();
        }

        return $counter;
    }

    public static function getCounters($search = FALSE, $state = FALSE, $city = FALSE, $contract = FALSE) {
        $collections = [
            "cities" => [],
            "states" => []
        ];

        if ($contract != 'remoto') {
            $cities = Content::getCities($search, $state, $city);
            $states = Content::getStates($search, $state, $city);
            $collections = [
                "cities" => $cities,
                "states" => $states
            ];
        }

        $hiringPosition = Content::getHiringModel($search, $state, $city, $contract);
        $collections["contract_type"] = $hiringPosition;

        return collect(["counters" => $collections]);
    }

    public static function searchPositions($search, $state = NULL, $city = NULL, $contract = NULL) {

        $search = request()->input("q");
        $searchEscaped = addslashes($search);
        $city = request()->input("city");

        $content = Content::selectRaw("id, type, image, title, group_id, date, description,city as 'cidade', state as 'estado', url, source, created_at,user_id")
                ->where("type", 1)
                ->whereIn('status', array('publicada', 'reposicao'))
                ->selectRaw("(
                                (match (title) against ('{$searchEscaped}' in boolean mode) * 10)
                                + match (description) against ('{$searchEscaped}' in boolean mode)
                                - (ABS(DATEDIFF(`date`, NOW())) / 10)
                            ) as score")
                ->when($search, function ($query) use ($search) {
                    return $query->whereRaw('match (title, description) against (? in boolean mode)', [$search]);
                })
                ->when($city, function ($query) use ($city) {
                    return $query->where("city", $city);
                })
                ->when($state, function ($query) use ($state) {
                    return $query->where("state", $state);
                })
                ->when($contract == 'remote', function ($query) use ($contract) {
                    return $query->where(function ($query) use ($contract) {
                        return $query->where(function ($query) use ($contract) {
                            $query
//                                                ->whereNull("user_id")
                            ->where("city", "")
                            ->where("state", "");
                        })
//                                            ->orWhere(function($query) use($contract) {
//                                                $query
////                                                ->whereNotNull("user_id")
////                                                        ->where("contract_type", "remoto");
//                                                        ->where("state", "");
//                                            })
                        ;
                    });
                })
                ->when($contract == 'local', function ($query) use ($contract) {
                    return $query->where(function ($query) use ($contract) {
                        return $query->where(function ($query) use ($contract) {
                            $query
//                                                            ->whereNull("user_id")
                            ->where("city", "!=", "")
                            ->where("state", "!=", "")
                            ;
                        })
//                                                ->orWhere(function($query) use($contract) {
//                                                    $query->whereNotNull("user_id")
//                                                            ->where("contract_type", "presencial");
//                                                })
                        ;
                    });
                })
                ->orderBy('score', 'desc')
                ->orderBy('id', 'desc')
                ->orderBy('ordenation')
                ->paginate(12)
        ;

//        dd(\App\Http\Controllers\Controller::getEloquentSqlWithBindings($content));                            
        $content->data = Content::hideFields($content);
        return $content;
    }

// Types
// 1 => position
// 2 => ad
// 3 => content


    public function getApplicationType() {
        return ( filter_var($this->application, FILTER_VALIDATE_EMAIL) ) ? 'email' : 'url';
    }

    /**
     * 
     * @return User
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function notifications() {
        return $this->belongsTo(Notification::class);
    }

    public function transaction() {
        return $this->hasOne(Transaction::class);
    }

    public static function getType($type) {
        switch ($type) {
            case "position":
                return 1;
                break;
            case "ad":
                return 2;
                break;
            case "content":
                return 3;
                break;
        }
    }

    public function getStatusName() {
        switch ($this->status) {
            case "aguardando_aprovacao":
                return 'Aguardando aprovação';
                break;
            case "aguardando_pagamento":
                return 'Aguardando pagamento';
                break;
            case "reposicao":
                return 'Reposição';
                break;
            case "publicada":
                return 'Publicada';
                break;
            case "reprovada":
                return 'Reprovada';
                break;
            case "expirada":
                return 'Expirada';
                break;
            case "fechada":
                return 'Fechada';
                break;
            case "cancelada":
                return 'Cancelada';
                break;
        }
    }

    public function checkExistenceOfPositionByCnpj() {
        $user = $this->user;

        if (!$user) {
            return false;
        }

        return Content::where('id', '!=', $this->id)
                        ->where(function ($query) {
                            $query->whereIn('status', array('publicada', 'reposicao'))
                            ->orWhere('status', 'aguardando_pagamento');
                        })
                        ->whereHas('user', function ($q) use ($user) {
                            $q->where('usegetrs.cnpj', $user->cnpj);
                        })->exists();
    }

    public function notifyPositionCreated() {
        $user = $this->user;

        $data = [
            'user_id' => $user->id,
            'content_id' => $this->id,
            'message' => "Recebemos a sua vaga para “{$this->title}”, agora nossa equipe vai analisar se está tudo certo, ok? Pode deixar que nossa equipe em breve vai avisar você sobre as próximas etapas. ",
            'active' => 1,
        ];

        Notification::create($data);

        Mail::send(new PositionCreated($this));
    }

    public function notifyPositionApproved() {
        $user = $this->user;

        $data = [
            'user_id' => $user->id,
            'content_id' => $this->id,
            'message' => "A sua vaga para “{$this->title}” foi aprovada! Clique no botão abaixo e realize o pagamento. ",
            'active' => 1,
        ];

        Notification::create($data);

        Mail::send(new PositionApproved($this));
    }

    public function notifyPositionPublished() {
        $user = $this->user;

        $data = [
            'user_id' => $user->id,
            'content_id' => $this->id,
            'message' => "A sua vaga para “{$this->title}” foi publicada! A partir de agora os candidatos já podem acessá-la e se candidatar. ",
            'active' => 1,
        ];

        Notification::create($data);

        Mail::send(new PositionPublished($this));
    }

    public function getStatusFront() {
        switch ($this->status) {
            case "aguardando_aprovacao":
                return array(
                    'title' => 'Aguardando',
                    'color' => "Default",
                    'value' => $this->getStatusName(),
                    'ref' => 03
                );
                break;
            case "aguardando_pagamento":
                return array(
                    'title' => 'Aguardando',
                    'color' => "Default",
                    'value' => $this->getStatusName(),
                    'ref' => 03
                );
                break;
            case "publicada":
                return array(
                    'title' => 'Recrutando',
                    'color' => "Default",
                    'value' => $this->getStatusName(),
                    'ref' => 02
                );

                break;
            case "reprovada":
                return array(
                    'title' => 'Cancelado',
                    'color' => "Primary",
                    'value' => $this->getStatusName(),
                    'ref' => 01
                );
                break;
            case "expirada":
                return array(
                    'title' => 'Cancelado',
                    'color' => "Primary",
                    'value' => $this->getStatusName(),
                    'ref' => 01
                );
                break;
            case "fechada":
                return array(
                    'title' => 'Concluída',
                    'color' => "Secondary",
                    'value' => $this->getStatusName(),
                    'ref' => 04
                );
                break;
            case "cancelada":
                return array(
                    'title' => 'Cancelado',
                    'color' => "Primary",
                    'value' => $this->getStatusName(),
                    'ref' => 01
                );
                break;
        }
    }

    protected static function booted() {
        static::deleting(function ($content) {
            $content->transaction()->delete();
        });
    }

    public static function inkluaUsersContent() {
        return Content::
                        where('type', 1)
                        ->whereIN('contents.user_id', InkluaUser::inkluaUsers()->get()->pluck('user_id'));
    }

    public static function StatusName($status) {
        switch ($status) {
            case "aguardando_aprovacao":
                return 'Aguardando aprovação';
                break;
            case "aguardando_pagamento":
                return 'Aguardando pagamento';
                break;
            case "reposicao":
                return 'Reposição';
                break;
            case "publicada":
                return 'Publicada';
                break;
            case "reprovada":
                return 'Reprovada';
                break;
            case "expirada":
                return 'Expirada';
                break;
            case "fechada":
                return 'Fechada';
                break;
            case "cancelada":
                return 'Cancelada';
                break;
        }
    }

    public static function ListStatusName() {
        $status = array();

        foreach (self::$status as $sta) {
            $status[$sta] = self::StatusName($sta);
        }
//         dd($status );
        return $status;
    }

    public function getLikesCount() {
        $count = JobLike::where('job_id', $this->id)->orderBy('created_at')->count();
        $count += ExternalLike::find($this->id) != null ? ExternalLike::find($this->id)->likes : 0;
        return $count;
    }

    public function getLikes() {
        return Candidate::whereIn('id', JobLike::where('job_id', $this->id)->orderBy('created_at')->pluck('candidate_id'))->get();
    }

    public function carteira() {
        $cc = $this->contentclient();
        if ($cc == null)
            return 0;
        $tax = $cc->clientcondition()->first()->tax;
        return $cc->vacancy * ($tax / 100) * $this->salary;
    }

    public function hasPermission($request) {
        $user = Auth::user();
        if ($user == null)
            $user = auth()->guard('api')->user();
//        se for seu ok
        if ($request->exists('debug')) {
            dd($user->id, $this->user_id);
        }
        if ($user->id == $this->user_id)
            return true;
//        se lider
        else if ($user->isInkluaLider()) {
            if ($request->exists('debug2')) {
                dd($user->inklua()->office_id, $this->office_id);
            }
//            se for lider do seu escritorio ok
            if ($user->inklua()->office_id == $this->office_id)
                return true;
        }

        return false;
    }

    public function toArray() {
        $data = parent::toArray();
        if (isset($data['salary']))
            $data['salary'] = floatval($data['salary']);
        if ($this->type == 'position')
            $data['subscribers'] = $this->getLikesCount();
        return $data;
    }

}
