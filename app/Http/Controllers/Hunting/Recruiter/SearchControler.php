<?php

namespace App\Http\Controllers\Hunting\Recruiter;

use App\Models\State;
use App\Models\CandidateHunting as Candidate;
use App\Models\CandidateEnglishLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Content;

class SearchControler extends Controller {

    private $payment_max = array(
        '1000',
        '2000',
        '30000',
        '4000',
        '5000',
        '99999',
    );
    private $candidates;

    
      public function moreDetails(Request $request, $id) {
        $content = Content::findOrFail($id);
        
        $data = array();
        $data['title'] = $content->title;
        $data['city'] = $content->city.'';
        $data['state'] = $content->state.'';
        $data['date'] = $content->published_at != null ?  $content->published_at->format('d/m/Y') :  $content->created_at->format('d/m/Y');        
        $data['total_candidates'] = $content->getLikesCount();
        
       

        return array('data' =>
            array('listing' => $data)
            );
    }

    
    
    public function index_search(Request $request) {

        $result = $this->search($request);
//          dd ($result);
//        dd ($request->input('cursor'));


        $filters = $this->getFilterFields($result['search'], $result['search']);
        $this->candidates = $result['search']->orderBy('candidate_hunting.updated_at', 'desc')->paginate(10);

//        dd($page);
        $this->candidates->items();

//        dd($result['search_nofilter']->count());
//        if ($result['search_nofilter']->count() == 0) x`
//        dd($result['param']);
        return response()->json(array(
//            'statesAll' => State::all(),
//            'is_home' => false,
//            'states_all' => State::all(),
                    'filters' => $filters,
//            'filtered' => $result['filtered'],
//            'payment_max' => $this->payment_max,
//            'english_levels' => CandidateEnglishLevel::all(),
                    'candidates' => $this->candidates->items(),
                    'paginator' => $this->candidates,
//            'param' => $result['param']
        ));
    }

    public function search_more(Request $request) {

        $result = $this->search($request);
        $filters = $this->getFilterFields($result['search'], $result['search_nofilter']);
//            dd($filters);
        $candidates = $result['search']->orderBy('updated_at', 'desc');
        if ($request->input('page') != null) {
            $candidates->skip(10 * $request->input('page'))->take(10);
        }
//        dd ($candidates );

        return view('desktop.retorno', array(
            'candidates' => $candidates->paginate(10)->items(),
            'filters' => $filters,
            'filtered' => $result['filtered'],
            'param' => $result['param']
        ));
    }

    public function detail($gid) {

        $unused = array(
            'cellphone',
            'created_at',
            'cv_url',
            'email',
            'CID',
            'full_name',
            'id',
            'remote',
            'role_id',
            'state_id',
            'status_id',
        );
        $data = array();
        $candidate = Candidate::where('gid', $gid)->first();
        $data = $candidate->toArray();
        $data['gid'] = 'KUNLA-' . $candidate->id;
        $data['city'] = $data['city'] . ' - ' . $candidate->state()->UF;
        $data['role'] = $candidate->title;
        $data['payment'] = $candidate->payment_formatted();
        $data['payment_2'] = $candidate->payment_formatted();
        $data['english_level'] = $candidate->english_level_obj()->level;
        $data['published_at'] = $candidate->published_at->format('d/m/Y H:i');
        $data['updated_at'] = $candidate->updated_at->format('d/m/Y H:i');
        $data['move_out'] = $data['move_out'] == 0 ? 'Não possui disponibilidade de mudança' : 'Possui disponibilidade de mudança';
        foreach ($unused as $u) {
            unset($data[$u]);
        }
        return json_encode($data);
    }

    public function getFilterFields($search, $search_nofiltered) {
        DB::enableQueryLog();
//                dd($search->toSql());

        $city = clone $search;
        $levels = clone $search;
        $remote = clone $search;
        $states = clone $search;
        $payments = clone $search;
        $payment_count = array();

//       dd ($candidates);
//        dd(State::whereIn('id', $candidates->pluck('state_id'))->get());
//        $city = $city->join('city', 'candidate_hunting.city_id', '=', 'city.id');
        $city = $city->
                        select('city.name'
                                ,
                                DB::raw('count(candidate_hunting.city_id) as total')
                        )
                        ->groupBy('candidate_hunting.city_id')
                        ->groupBy('city.name')
                        ->orderBy('total', 'desc')->get()->toArray();
//        dd($city);
        $city = $this->adjustArray($city, 'name', 'total');
        $levels = $levels->
                select('english_level',
                        DB::raw('count(english_level) as total'),
                        'candidate_english_level.level')
                ->join('candidate_english_level', 'candidate_hunting.english_level', '=', 'candidate_english_level.id')
                ->groupBy('english_level')
                ->groupBy('candidate_english_level.level')
                ->orderBy('candidate_english_level.id', 'desc');
//                dd($levels->toSql());
        $levels = $levels->get()->toArray();
        $levels = array_reverse($levels);
//       $levels = $this->adjustArray($levels, 'english_level','total');

        $remote = $remote->
                        select('remote',
                                DB::raw('count(remote) as total')
                        )
                        ->groupBy('remote')
                        ->orderBy('remote')->
                        get()->toArray();
        $remote = $this->adjustArray($remote, 'remote', 'total');
        $states = $states->
                        select('state_id', 'state.name'
                                ,
                                DB::raw('count(state_id) as total')
                        )
                        ->distinct('state_id')
                        ->join('state', 'candidate_hunting.state_id', '=', 'state.id')
                        ->groupBy('state_id')
                        ->groupBy('state.name')
                        ->orderBy('total', 'DESC')
                        ->get()->toArray();
//dd($states);
        $payments_result = arraY();
        foreach ($this->payment_max as $p) {
            $payments_result[$p] = clone $payments;
            if ($p == 1000) {
                $p_min = "" . (0);
            }
            if ($p == 2000) {
                $p_min = "" . (1000);
            }
            if ($p == 3000) {
                $p_min = "" . (2000);
            }
            if ($p == 4000) {
                $p_min = "" . (3000);
            }
            if ($p == 5000) {
                $p_min = "" . (4000);
            }
            if ($p == 99999) {
                $p_min = "" . (5000);
            }

            $payments_result[$p] = $payments_result[$p]
                    ->where('payment', '<', $p)
                    ->where('payment', '>=', $p_min);
//                            dd($payments_result[$p]->toSql()); 
            $payments_result[$p] = $payments_result[$p]->count();
        }

        return array(
            'city' => $city,
            'levels' => $levels,
            'remote' => $remote,
            'states' => $states,
            'payments' => $payments_result,
        );
    }

    public function adjustArray($array, $key, $value) {
        $retornoo = array();
        foreach ($array as $a) {
            $retornoo[$a[$key]] = $a[$value];
        }

        return $retornoo;
    }

    private $searchble = array(
        'key' => '%',
        'location' => '%',
        'payment_min' => 'min',
        'payment_max' => 'max',
        'state_id' => 'in',
        'city' => 'in',
        'remote' => 'in',
        'english_level' => 'in',
        'level_education_id' => 'in',
        'first_job' => '=',
        'pcd' => '=',
        'pcd_type_id' => '=',
        'candidate_hunting.updated_at_min' => 'min',
        'candidate_hunting.updated_at_max' => 'max'
    );

    /*
     * @return \Illuminate\Http\Response
     */

    public function search(Request $request) {


//        dd ($request->all());

        $data = $request->all();

        if (isset($data['state_id'])) {
            if ($data['state_id'] != '') {
                $states[] = $data['state_id'];
                $data['state_id'] = $states;
            } else {
                unset($data['state_id']);
            }
        }
        $states = array();
        foreach ($data as $d => $val) {
            if (strpos($d, 'state_id_') !== false) {
                unset($data[$d]);
                $states[str_replace('state_id_', '', $d)] = str_replace('state_id_', '', $d);
                $data['state_id'] = $states;
            }
        }
        $filtered['state_id'] = $states;
        $citys = array();

        foreach ($data as $d => $val) {
            if (strpos($d, 'city_') !== false) {
                unset($data[$d]);
                $citys[str_replace('city_', '', $d)] = str_replace('city_', '', $d);
                $data['city'] = $citys;
            }
        }
        $filtered['city'] = $citys;

        $english_levl = array();
        foreach ($data as $d => $val) {
            if (strpos($d, 'english_level_') !== false) {
                unset($data[$d]);
                $english_levl[str_replace('english_level_', '', $d)] = str_replace('english_level_', '', $d);
                $data['english_level'] = $english_levl;
            }
        }
        $filtered['english_level'] = $english_levl;
        $remotes = array();
        foreach ($data as $d => $val) {
            if (strpos($d, 'remote_') !== false) {
                unset($data[$d]);
                $remotes[str_replace('remote_', '', $d)] = str_replace('remote_', '', $d);
                $data['remote'] = $remotes;
            }
        }
        $filtered['remote'] = $remotes;

        $level_education = array();
        foreach ($data as $d => $val) {
            if (strpos($d, 'level_education_id_') !== false) {
                unset($data[$d]);
                $level_education[str_replace('level_education_id_', '', $d)] = str_replace('level_education_id_', '', $d);
                $data['level_education_id'] = $level_education;
            }
        }
        $filtered['level_education_id'] = $level_education;

        if (isset($data['candidate_hunting.updated_at_max'])) {
            if ($data['candidate_hunting.updated_at_max'] == 'week') {
                $data['candidate_hunting.updated_at_max'] = Carbon::now()->subWeek(1);
            }
            if ($data['candidate_hunting.updated_at_max'] == '1 month') {
                $data['candidate_hunting.updated_at_max'] = Carbon::now()->subMonth(1);
            }
            if ($data['candidate_hunting.updated_at_max'] == '3 month') {
                $data['candidate_hunting.updated_at_max'] = Carbon::now()->subMonth(3);
            }
            if ($data['candidate_hunting.updated_at_max'] == '6 month') {
                $data['candidate_hunting.updated_at_max'] = Carbon::now()->subMonth(6);
            }
            if ($data['candidate_hunting.updated_at_max'] == '1 year') {
                $data['candidate_hunting.updated_at_max'] = Carbon::now()->subYear(6);
            }
        }

        if (isset($data['payment_max'])) {
            if ($data['payment_max'] == 1000) {
                $data['payment_min'] = "" . (0000);
            }
            if ($data['payment_max'] == 2000) {
                $data['payment_min'] = "" . (1000);
            }
            if ($data['payment_max'] == 3000) {
                $data['payment_min'] = "" . (2000);
            }
            if ($data['payment_max'] == 4000) {
                $data['payment_min'] = "" . (3000);
            }
            if ($data['payment_max'] == 5000) {
                $data['payment_min'] = "" . (4000);
            }
            if ($data['payment_max'] == 99999) {
                $data['payment_min'] = "" . (5000);
            }



            $filtered['payment_min'] = $data['payment_min'];
            $filtered['payment_max'] = $data['payment_max'];
        }
//        dd($data);
//          
//       dd($data);
        $param = array();
        /**
         * @var $search 
         */
//        $search = new luminate\Database\Eloquent\Builder();
        $search = Candidate::select('candidate_hunting.*');
        $search = $search->whereRaw('status NOT IN (-1) OR status is null');
        $search_nofilter = clone $search;
        foreach ($this->searchble as $key => $val) {

            if (array_key_exists($key, $data)) {

                if ($data[$key] != '') {
//                   
                    if ($val == '=') {

                        $search = $search->where($key, $request->input($key));
                    }

                    if ($val == 'in') {
//                        dd('in');
                        if (is_array($data[$key])) {
                            if ($key == 'level_education_id') {
                                $search = $search->join('candidate_education_hunting', 'candidate_hunting.id', '=', 'candidate_education_hunting.candidate_id');
                            }
                            $search = $search->whereIn($key, $data[$key]);
//                            dd($data['city']);
                        } else {
//                            dd('not in');
                            $search = $search->where($key, $data[$key]);
                        }
                    }
                    if ($val == '%') {
                        if ($key == 'key') {
                            $search = $search->whereRaw("(candidate_hunting.name like '%$data[$key]%'  or "
                                    . "candidate_hunting.surname like '%$data[$key]%'  or "
                                    . "candidate_hunting.cellphone like '%$data[$key]%'  or "
                                    . "candidate_hunting.id = '$data[$key]'  "
                                    . ") ");
                        }
                        if ($key == 'location') {
                            $param[$key] = $request->input($key);

                            $search = $search->where("city.name", 'like', "$val$data[$key]$val");
                        }
                    }
                    if ($val == 'min' || $val == 'max') {
//                        if (!isset($filtered['payment_max'])) {
                        $search = $search->where(str_replace('_' . $val, '', $key),
                                $val == 'min' ? '>=' : '<',
                                $data[$key]);
//                        }
                    }
                }
            }
        }

        $search = $search->join('city', 'candidate_hunting.city_id', '=', 'city.id');
        if ($request->has('order_by')) {
            foreach ($request->input('order_by')[0] as $order => $type) {
//                      
                $search->orderBy($order, $type);
                if ($order == 'candidate_role.role') {
                    $search = $search->join('candidate_role', 'candidate_hunting.role_id', '=', 'candidate_role.id');
                    $search->orderBy($order, $type);
                }
                if ($order == 'state.name') {
                    $search = $search->join('state', 'candidate_hunting.state_id', '=', 'state.id');
                    $search->orderBy($order, $type);
                }
            }
        }

        if ($request->exists('debug')) {
            dd(self::getEloquentSqlWithBindings($search));
        }
        return array(
            'search' => $search,
            'param' => $param,
            'filtered' => $filtered,
            'search_nofilter' => $search_nofilter
        );
    }

    public function validate_data($request) {

        $data = $this->validate($request, $this->rules);
        $data['cellphone'] = trim(str_replace(array('(', ')', '-'), '', $data['cellphone']));
        $data['payment'] = trim(str_replace(array('R$', '.', ','), '', $data['payment']));

        return $data;
    }

    public static function getEloquentSqlWithBindings($query) {
        return vsprintf(str_replace('?', '%s', str_replace('%', '%%', $query->toSql())), collect($query->getBindings())->map(function ($binding) {
                    return is_numeric($binding) ? $binding : "'{$binding}'";
                })->toArray());
    }

}
