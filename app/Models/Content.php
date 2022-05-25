<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Group;

class Content extends Model
{
    use HasFactory;

       protected $fillable = [
        'user_id',
        'title',
        'contract_type',
        'salary',
        'image',
        'description',
        'application',
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
    ];


    protected $hidden = [
        // 'id'
        'row_n',
        'order_type'
    ];

    public function group(){
        return $this->belongsTo('App\Models\Group');
    }

    public function actions()
    {
        return $this->hasMany(\App\Models\Action::class);
    }

    public static function getRandomImage(){
        $img = random_int(1,16);
        return url("/img/{$img}.png");
    }

    public static function getHomeContent(){
        $type = request()->input("tipo");
        $user = auth()->guard('api')->user();
        

        $content = Content::selectRaw("id, type, image, title, category, group_id, date, description,city as 'cidade', state as 'estado', url, source, created_at")                        
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

    public static function hideFields($data){
        return $data->makeHidden(['ordenation', 'compleo_code', 'cod_filial', 'branch_code', 'branch_name', 'updated_at', 'row_n', 'in_compleo']);
    }

        public static function getCities($search = FALSE, $state = FALSE, $city = FALSE){
        return Content::select("city as name", DB::raw('count(*) as positions'))
                        ->where("type", 1)
//                        ->where('status', 'publicada')
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

    public static function getStates($search = FALSE, $state = FALSE, $city = FALSE){
        return Content::select("state as name", DB::raw('count(*) as positions'))
                        ->where("type", 1)
//                        ->where('status', 'publicada')
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

    public static function getHiringModel($search = FALSE, $state = FALSE, $city = FALSE, $contract = FALSE){
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

        if($contract != 'presencial'){
            $remote = Content::where("type", 1)
//                            ->where('status', 'publicada')
                            ->where("city","")
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


        if($contract != 'remoto'){
            $inPerson = Content::where("type", 1)
//                            ->where('status', 'publicada')
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

     public static function getCounters($search = FALSE, $state = FALSE, $city = FALSE, $contract = FALSE){
        $collections = [
            "cities" => [],
            "states" => []
        ];

        if($contract != 'remoto') {
            $cities = Content::getCities($search, $state, $city);
            $states = Content::getStates($search, $state, $city);
            $collections = [
                "cities" => $cities,
                "states" => $states
            ];
        }
        
        $hiringPosition = Content::getHiringModel($search, $state, $city, $contract);
        $collections["contract_type"] = $hiringPosition;

        return collect([ "counters" => $collections]);
    }

    public static function searchPositions($search, $state = NULL, $city = NULL, $contract = NULL){

        $search = request()->input("q");
        $searchEscaped = addslashes($search);
        $city = request()->input("city");

        $content = Content::selectRaw("id, type, image, title, group_id, date, description,city as 'cidade', state as 'estado', url, source, created_at")
                                ->where("type", 1)
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
                                return $query->where(function($query) use($contract) {
                                    return $query->where(function($query) use($contract) {
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
                                return $query->where(function($query) use($contract) {
                                        return $query->where(function($query) use($contract) {
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
}
