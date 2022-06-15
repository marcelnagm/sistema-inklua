<?php

namespace App\Http\Controllers\MapeamentoTech;

use App\Models\State;
use App\Models\Candidate;
use App\Models\CandidateEnglishLevel;
use Illuminate\Http\Request;
use \App\Models\CandidateRole;
use App\Http\Controllers\MapeamentoTech\ApiControler;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SearchControler extends Controller {

    private $payment_max = array(
        '5000',
        '8000',
        '10000',
        '15000',
        '20000',
        '99999',
    );
    private $candidates;

    public function index_search(Request $request) {

        $result = (new ApiControler)->search($request);
//          dd ($result);
//        dd ($request->input('cursor'));


        $filters = $this->getFilterFields($result['search'], $result['search']);
        $this->candidates = $result['search']->orderBy('candidate.updated_at', 'desc')->paginate(10);

//        dd($page);
        $this->candidates->take(10);

//        dd($result['search_nofilter']->count());
        if ($result['search_nofilter']->count() == 0) {
            return response()->json([]);
        }

        $temp = array();
        foreach ($this->candidates as $cand) {          
            $data = $cand->toArray(true);
            $data['city'] = $cand->city .' - '.State::find($cand->state_id)->UF;
            $temp[] = $data;
        }
        $this->candidates = $temp;
//        dd($result['param']);
        return response()->json([
                    'statesAll' => State::all(),
                    'is_home' => false,
                    'states_all' => State::all(),
                    'roles' => CandidateRole::all(),
                    'filters' => $filters,
                    'filtered' => $result['filtered'],
                    'payment_max' => $this->payment_max,
                    'english_levels' => CandidateEnglishLevel::all(),
                    'candidates' => $this->candidates,
                    'paginator' => $this->candidates,
                    'param' => $result['param']
        ]);
    }

    public function search_more(Request $request) {

        $result = (new ApiControler)->search($request);
        $filters = $this->getFilterFields($result['search'], $result['search_nofilter']);
//            dd($filters);
        $candidates = $result['search']->orderBy('updated_at', 'desc');
        if ($request->input('page') != null) {
            $candidates->skip(10 * $request->input('page'))->take(10);
        }
//        dd ($candidates );
        $temp = array();
        foreach ($candidates->paginate(10)->items() as $cand) {
            $data = $cand->toArray(true);
            $data['city'] = $cand->city .' - '.State::find($cand->state_id)->UF;
                $temp[] = $data;
        }
        $candidates = $temp;

        return response()->json([
                    'candidates' => $candidates,
                    'filters' => $filters,
                    'filtered' => $result['filtered'],
                    'param' => $result['param']
        ]);
    }

    public function detail($gid) {



        $candidate = Candidate::where('gid', $gid)->
                where('published_at', '<>', 'NULL')->
                first();
        if ($candidate != null) {
            $data = array();
            $data = $candidate->toArray(true);
            $data['gid'] = 'INKLUA#' . $candidate->id;
            $data['city'] = $data['city'] . ' - ' . $candidate->state()->UF;
            $data['role'] = $candidate->title;
            $data['payment'] = $candidate->payment_formatted();
            $data['payment_2'] = $candidate->payment_formatted();
            $data['english_level'] = $candidate->english_level_obj()->level;
            $data['pcd_type'] = $candidate->pcd_typo();
            $data['published_at'] = $candidate->published_at->format('d/m/Y H:i');
            $data['updated_at'] = $candidate->updated_at->format('d/m/Y H:i');
            $data['move_out'] = $data['move_out'] == 0 ? 'Não possui disponibilidade de mudança' : 'Possui disponibilidade de mudança';
            unset($data['pcd_type']);
            return json_encode($data);
        } else {
            return json_encode([]);
        }
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
        $city = $city->
                        select('city'
                                ,
                                DB::raw('count(city) as total')
                        )
                        ->groupBy('city')->orderBy('total', 'desc')->get()->toArray();
        $city = $this->adjustArray($city, 'city', 'total');
        $levels = $levels->
                select('english_level',
                        DB::raw('count(english_level) as total'),
                        'candidate_english_level.level')
                ->join('candidate_english_level', 'candidate.english_level', '=', 'candidate_english_level.id')
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
                        ->join('state', 'candidate.state_id', '=', 'state.id')
                        ->groupBy('state_id')
                        ->groupBy('state.name')
                        ->orderBy('total', 'DESC')
                        ->get()->toArray();
//dd($states);
        $payments_result = arraY();
        foreach ($this->payment_max as $p) {
            $payments_result[$p] = clone $payments;
            if ($p == 5000) {
                $p_min = "" . (0);
            }
            if ($p == 8000) {
                $p_min = "" . (5000);
            }
            if ($p == 10000) {
                $p_min = "" . (8000);
            }
            if ($p == 15000) {
                $p_min = "" . (10000);
            }
            if ($p == 20000) {
                $p_min = "" . (15000);
            }
            if ($p == 99999) {
                $p_min = "" . (20000);
            }

            $payments_result[$p] = $payments_result[$p]
                    ->where('payment', '<', $p)
                    ->where('payment', '>=', $p_min);
//                            dd($payments_result[$p]->toSql()); 
            $payments_result[$p] = $payments_result[$p]->count();
        }

        return array(
            'remote' => $remote,
            'states' => $states,
            'city' => $city,
            'payments' => $payments_result,
            'levels' => $levels,
        );
    }

    public function adjustArray($array, $key, $value) {
        $retornoo = array();
        foreach ($array as $a) {
            $retornoo[$a[$key]] = $a[$value];
        }

        return $retornoo;
    }

}
