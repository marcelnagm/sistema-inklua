@extends('layouts.cms')

@section('template_title')
Candidatos
@endsection

@section('content')
<div class="col-xl-12">
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h5 class="m-0 font-weight-bold text-primary">Lista de Candidato - Mapeamento Tech
            </h5>
        </div>                


        <div class="float-right p-4">
            <a href="{{ route('candidate.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                Adicionar novo candidato
            </a>


           
       </div>

        <div class="card-body">
             @if(Session::has('search'))

            <span class="alert danger"> Resultado Filtrado <a href="{{ route('candidate.clear') }}"class="btn btn-danger btn-sm" >Limpar Filtro</a></span>
            @endif
            <button class="btn btn-primary" onclick="$('#filtros').toggle('100');"> Filtros</button>
            <div class="p-4"  id="filtros" style="width: 100%;display: none;">                       
                <form method="post" action="{{ route('candidate.search') }}"  role="form" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group form-control-lg" >
                        <table>
                            <thead>
                            <th colspan="2">

                            </th>
                            <th>
                                Status 
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
                                @if(Session::has('search'))
                                <td>
                                    {{ Form::text('search', Session::get('search')['param'],['class' => 'form-control','placeholder' => 'Nome, telefone ou código' ]) }}                            
                                </td>
                                <td>
                                    {{ Form::select('status_id',$status,Session::get('search')['status'],['class' => 'form-control form-control-sm' ]) }}
                                </td>
                                <td>
                                    {{ Form::select('race_id',isset($races) ? $races : $race,Session::get('search')['race'],['class' => 'form-control' ]) }}
                                </td>
                                <td>
                                    {{ Form::select('gender_id',isset($genders) ? $genders : $gender,Session::get('search')['gender'],['class' => 'form-control' ]) }}
                                </td>
                                @else
                                <td>
                                    {{ Form::text('search', '',['class' => '','placeholder' => 'Nome, telefone ou código' ]) }}                            
                                </td>
                                <td>
                                    {{ Form::select('status_id',$status,'',['class' => '' ]) }}
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
            <div class="table-responsive p-md-4">
                <table class="table table-striped table-hover">
                    <thead class="thead">
                        <tr>
                            <th>Código</th>
                            <th>Titulo</th>
                            <th>Local</th>
                            <th>Pretensão salarial</th>
                            <th>Data Publicação</th>
                            <th>Nome</th>
                            <th>Telefone</th>
                            <th>Status</th>                                 
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($candidates as $candidate)
                        <tr>
                            <td>INKLUA#{{ $candidate->id}}</td>
                            <td>{{ $candidate->title }}</td>
                            <td>{{ $candidate->city }} - {{ $candidate->state()->UF }}</td>
                            <td>R${{ $candidate->payment_formatted() }}</td>                                    
                            <td>{{$candidate->published_at != null ?$candidate->published_at->format('d/m/Y H:i') : 'Não publicado'}}</td>
                            <td>{{ $candidate->full_name }}</td>
                            <td>{{ $candidate->phone() }}</td>
                            <td>{{ $candidate->status() }}</td>
                            <td>                                        
                                <a class="btn btn-sm btn-success" href="{{ route('tech.edit',$candidate->id) }}"><i class="fa fa-fw fa-edit"></i> Editar</a>                                         
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {!! $candidates->links() !!}
</div>
</div>
</div>
@endsection
