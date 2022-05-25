@extends('layouts.cms')

@section('template_title')
Candidate Hunting
@endsection

@section('content')

<div class="col-xl-12">
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h5 class="m-0 font-weight-bold text-primary">Lista de Candidato - Hunting
            </h5>
        </div>                

        <div class="float-right">
            <a href="{{ route('candidate-hunt.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                Novo Candidato
            </a>
        </div>
        <div class="float-right">
                    <button class="btn btn-primary btn" onclick="$('#filtros').toggle('100');"> Filtros</button>
                    @if(Session::has('hunt'))

                    <span class="alert danger"> Resultado Filtrado <a href="{{ route('hunt.clear') }}"class="btn btn-danger btn-sm" >Limpar Filtro</a></span>
                    @endif
                    <div  id="filtros" style="display: none;">                       
                        <form method="post" action="{{ route('hunt.search') }}"  role="form" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group form-control-lg" >
                                <table>
                                    <thead>
                                    <th colspan="2">

                                    </th>                                    
                                    <th>
                                        Raça 
                                    </th>
                                    <th>
                                        Genêro 
                                    </th>
                                    </thead>
                                    <tr>
                                        <td>
                                            {{ Form::label('Pesquisar por:') }}
                                        </td>
                                        @if(Session::has('hunt'))
                                        <td>
                                            {{ Form::text('search', Session::get('hunt')['param'],['class' => 'form-control','placeholder' => 'Nome, telefone ou código' ]) }}                            
                                        </td>                                        
                                        <td>
                                            {{ Form::select('race_id',isset($races) ? $races : $race,Session::get('hunt')['race'],['class' => 'form-control' ]) }}
                                        </td>
                                        <td>
                                            {{ Form::select('gender_id',isset($genders) ? $genders : $gender,Session::get('hunt')['gender'],['class' => 'form-control' ]) }}
                                        </td>
                                        @else
                                        <td>
                                            {{ Form::text('search', '',['class' => '','placeholder' => 'Nome, telefone ou código' ]) }}                            
                                        </td>
                                        <td>
                                            {{ Form::select('race_id',$race ?? '','',['class' => '' ]) }}
                                        </td>
                                        <td>
                                            {{ Form::select('gender_id',$gender,'',['class' => '' ]) }}
                                        </td>
                                        @endif
                                        <td>
                                        <button type="submit" class="btn btn-primary">Buscar</button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            
                            
                        </form>
                    </div>                    
                </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead">
                        <tr>
                            <th>Nome</th>                            
                            <th>Celular</th>
                            <th>Email</th>
                            <th>Pretensão Salarial</th>
                            <th>Pcd</th>
                            <th>Localidade</th>
                            <th>Primero Emprego</th>
                            <th>Remoto</th>
                            <th>Inglês</th>                            
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($candidateHuntings as $candidateHunting)
                        <tr>
                            <td>{{ $candidateHunting->name }} {{ $candidateHunting->surname }}</td>                            
                            <td>{{ $candidateHunting->cellphone }}</td>
                            <td>{{ $candidateHunting->email }}</td>
                            <td>
                                R$ {{ $candidateHunting->payment_formatted() }}
                            </td>
                            <td>
                            @include('layouts.partials.yesno',array('param' => $candidateHunting->pcd))                                                
                            </td>
                            <td>
                            {{ $candidateHunting->city() }}
                            </td>                            
                            <td>
                            @include('layouts.partials.yesno',array('param' => $candidateHunting->first_job))                        
                            </td>
                            <td>
                            @include('layouts.partials.yesno',array('param' => $candidateHunting->remote))                        
                            </td>                            
                            <td>{{ $candidateHunting->english_level_obj() }}</td>
                            <td>
                                
                                    <a class="btn btn-sm btn-primary " href="{{ route('candidate-hunt.show',$candidateHunting->id) }}"><i class="fa fa-fw fa-eye"></i> Exibir</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {!! $candidateHuntings->links() !!}
</div>
</div>
</div>
@endsection
