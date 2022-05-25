<?php

namespace App\Http\Controllers\MapeamentoTech;

use App\Models\Candidate;
use Illuminate\Http\Request;
use App\Models\CandidateEnglishLevel;
use App\Models\CandidateRole;
use App\Models\CandidateStatus;
use App\Models\State;
use App\Http\Controllers\MapeamentoTech\ApiControler;
use App\Models\CandidateRace;
use App\Models\CandidateGender;
use App\Http\Controllers\Controller; 

/**
 * Class CandidateController
 * @package App\Http\Controllers
 */
class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $candidates = Candidate::orderby('created_at','DESC')->paginate();
        
        $race = CandidateRace::orderBy('id')->get();
        $gender = CandidateGender::orderBy('id')->get();
        $status = CandidateStatus::select('id','status')->get()->toArray();
        $status = $this->adjustArray($status,'id','status');
        $status[0] = 'Todos os status';
        ksort($status,SORT_NUMERIC);
//        dd($status);
            return view('cms.mapeamento-tech.candidate.index', compact('candidates','status','race','gender'))
            ->with('i', (request()->input('page',1) - 1) * $candidates->perPage());
    }
    
    public function clear(Request $request)
    {
    $request->session()->forget('search');
    return redirect('/admin/candidate');
//    dd('')
    }
    
    public function search(Request $request)
    {
        
        if($request->has('page') ){
        $param =$request->session()->get('search')['param'];
        $status =$request->session()->get('search')['status'];
        $race =$request->session()->get('search')['race'];
        $gender =$request->session()->get('search')['gender'];
    }else{
        
        $param =$request->input('search');
        $status =$request->input('status_id');
        $race =$request->input('race_id');
        $gender =$request->input('gender_id');
//        dd($gender,$race);
        session([
            'search' => array(
                'param' =>$param,
                'status' => $status,
                'race' => $race,
                'gender' => $gender
                
                )
            ]);
        
    }
    
    
    
    
    
        $candidates = Candidate::
                whereRaw("(full_name like '%$param%'  or "
                        . "cellphone like '%$param%'  or "
                        . "id = '$param'  "
                        . ") ")->                
                orderby('created_at','DESC');
        
    if($status != 0){
        $candidates->where('status_id',$status);
    }
    if($race != 0){
        $candidates->where('race_id',$race+1);
    }
    if($gender!= 0){
        $candidates->where('gender_id',$gender+1);
    }
//        dd($this->getEloquentSqlWithBindings($candidates));
        $candidates= $candidates->paginate();
        
         $status = CandidateStatus::select('id','status')->get()->toArray();
        $status = $this->adjustArray($status,'id','status');
        $status[0] = 'Todos os status';
        ksort($status,SORT_NUMERIC);
          $races = CandidateRace::orderBy('id')->get();
        $genders = CandidateGender::orderBy('id')->get();
      
        return view('cms.mapeamento-tech.candidate.index', compact('candidates','status','races','genders'))
            ->with('i', (request()->input('page', 1) - 1) * $candidates->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $candidate = new Candidate();
        return view('cms.mapeamento-tech.candidate.create', array(
            'candidate' => $candidate,
             'english_levels' => CandidateEnglishLevel::all(),
            'states' => State::all(),
            'role' => CandidateRole::all(),
            'status' => CandidateStatus::all(),
            'race' => CandidateRace::all(),
            'gender' => CandidateGender::all()        
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        dd($request);
          $result = (new ApiControler)->store($request);
          
        return redirect()->route('candidate.index')
            ->with('success', 'Candidato cadastrado com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $candidate = Candidate::find($id);

        return view('cms.mapeamento-tech.candidate.show', compact('candidate'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $candidate = Candidate::find($id);

        return view('cms.mapeamento-tech.candidate.edit',  array(
            'candidate' => $candidate,
             'english_levels' => CandidateEnglishLevel::all(),
            'states' => State::all(),
            'role' => CandidateRole::all(),
            'status' => CandidateStatus::all(),
            'race' => CandidateRace::all(),
            'gender' => CandidateGender::all()        
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Candidate $candidate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Candidate $candidate)
    {
//        dd($request);
        
        $result = (new ApiControler)->update($request,$request->input('gid'));
          
//        dd($result);
        return redirect()->route('candidate.edit',$candidate)
            ->with('success', 'Candidato editado com sucesso');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($gid)
    {
        $candidate =Candidate::where('gid', $gid)->first();
        $candidate->delete();
        return redirect()->route('candidate.index')
            ->with('success', 'Candidato removido com sucesso');
    }
    
    
    public function publish(Request $request, $id) {
        $candidate = Candidate::find( $id);
        $candidate->status_id = 1;
        $candidate->published_at = date("Y-m-d H:i:s");
        $candidate->update();

        
        return redirect()->route('candidate.edit',$candidate)            
            ->with('success', 'Candidato publicado');
    }
    
    public function unpublish(Request $request, $id) {
        $candidate = Candidate::find( $id);
        $candidate->status_id = 2;
        $candidate->published_at = null;
        $candidate->update();

        
        return redirect()->route('candidate.edit',$candidate)                        
            ->with('success', 'Candidato arquivado');
    }
    
      public function detail(Request $request)
    {
//        $candidate = Candidate::find($id);
 $candidate = Candidate::where('id', str_replace('KUNLA#', '', $request->input('GID')))->first();
// dd($candidate);
        return view('cms.mapeamento-tech.candidate.show', compact('cms.mapeamento-tech.candidate'));
    }


    public function adjustArray($array, $key, $value) {
        $retornoo = array();
        foreach ($array as $a) {
            $retornoo[$a[$key]] = $a[$value];
        }

        return $retornoo;
    }
    
}
