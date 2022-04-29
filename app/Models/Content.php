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
        'title',
        'image',
        'description',
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
        'category'
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
                            ->paginate(12);

        // $content->data = Content::hideFields($content);
        return $content;
    }

    public static function hideFields($data){
        return $data->makeHidden(['ordenation', 'compleo_code', 'cod_filial', 'branch_code', 'branch_name', 'updated_at', 'row_n', 'in_compleo']);
    }

    public static function getCities($search = FALSE){
        return Content::select("city as name", DB::raw('count(*) as positions'))
                        ->where("type", 1)
                        ->whereNotNull("city")
                        ->where("city", "!=", "")
                        ->when(($search), function ($query) use ($search) {
                            $query->whereRaw('match (title, description) against (? in boolean mode)', [$search]);
                        })
                        ->groupBy("city")
                        ->orderBy("city")
                        ->get();
    }

    public static function searchPositions(){

        $search = request()->input("q");
        $searchEscaped = addslashes($search);
        $city = request()->input("city");

        $content = Content::selectRaw("id, type, image, title, group_id, date, description,city as 'cidade', state as 'estado', url, source, created_at")
                            ->selectRaw("(
                                (match (title) against ('{$searchEscaped}' in boolean mode) * 10)
                                + match (description) against ('{$searchEscaped}' in boolean mode)
                                - (ABS(DATEDIFF(`date`, NOW())) / 10)
                            ) as score")
                            ->where("type", 1)

                            // Filtro por query e city
                            ->when(($search != '' && $city  != ''), function ($query) use ($search, $city) {
                                return $query->where("city", $city)
                                            ->whereRaw('match (title, description) against (? in boolean mode)', [$search]);
                            })

                            // Filtro apenas por query
                            ->when(($search != '' && $city == ''), function ($query) use ($search) {
                                return $query->whereRaw('match (title, description) against (? in boolean mode)', [$search]);
                            })


                            // Filtro apenas por city
                            ->when(($city != '' && $search == ''), function ($query) use ($city) {
                                return $query->where("city", $city);
                            })
                            ->orderBy('score', 'desc')
                            ->orderBy('id', 'desc')
                            ->orderBy('ordenation')
                            ->paginate(12);

                            
        $content->data = Content::hideFields($content);
        return $content;
    }

    // Types
    // 1 => position
    // 2 => ad
    // 3 => content
}
