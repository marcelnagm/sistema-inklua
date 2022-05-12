@extends('layouts.cms')

@section('template_title')
Candidatos
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <h1 class="h3 mb-2 text-gray-800">Candidatos</h1>                            

                        <div class="float-right">
                            <a href="{{ route('candidate.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                Adicionar novo candidato
                            </a>
                        </div>
                    </div>
                </div>
                @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
                @endif
                <div class="float-right">
                    <button class="btn btn-primary btn" onclick="$('#filtros').toggle('100');"> Filtros</button>
                    @if(Session::has('search'))

                    <span class="alert danger"> Resultado Filtrado <a href="{{ route('candidate.clear') }}"class="btn btn-danger btn-sm" >Limpar Filtro</a></span>
                    @endif
                    <div  id="filtros" style="display: none;">                       
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
                                            {{ Form::text('search', Session::get('search')['param'],['class' => '','placeholder' => 'Nome, telefone ou código' ]) }}                            
                                        </td>
                                        <td>
                                            {{ Form::select('status_id',$status,Session::get('search')['status'],['class' => '' ]) }}
                                        </td>
                                        <td>
                                            {{ Form::select('race_id',isset($races) ? $races : $race,Session::get('search')['race'],['class' => '' ]) }}
                                        </td>
                                        <td>
                                            {{ Form::select('gender_id',isset($genders) ? $genders : $gender,Session::get('search')['gender'],['class' => '' ]) }}
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
                                    </tr>
                                </table>
                            </div>
                            <button type="submit" class="btn btn-primary">Buscar</button>
                        </form>
                    </div>
                </div>


                <div class="card-body">
                    <div class="table-responsive">
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
                                    <td>KUNLA#{{ $candidate->id}}</td>
                                    <td>{{ $candidate->title }}</td>
                                    <td>{{ $candidate->city_id }} - {{ $candidate->state()->UF }}</td>
                                    <td>R${{ $candidate->payment_formatted() }}</td>                                    
                                    <td>{{$candidate->published_at != null ?$candidate->published_at->format('d/m/Y H:i') : 'Não publicado'}}</td>
                                    <td>{{ $candidate->full_name }}</td>
                                    <td>{{ $candidate->phone() }}</td> 
                                    <td>
                                        <form action="{{ route('candidate.destroy',$candidate->id) }}" method="DELETE">
                                            <a class="btn btn-sm btn-primary " href="{{ route('candidate.show',$candidate->id) }}"><i class="fa fa-fw fa-eye"></i> Exibir</a>
                                            <a class="btn btn-sm btn-success" href="{{ route('candidate.edit',$candidate->id) }}"><i class="fa fa-fw fa-edit"></i> Editar</a>
                                            <a class="btn btn-sm btn-primary " href="@if ($candidate->published_at == null)  {{  route('candidate.publish',$candidate->id) }} @else {{  route('candidate.unpublish',$candidate->id) }} @endif"><i class="fa fa-fw fa-eye"></i> 
                                                @if($candidate->published_at == null)                                            
                                                Publicar
                                                @else
                                                Arquivar
                                                @endif
                                            </a>
                                            <a class="btn btn-sm btn-danger" href="{{ route('candidate.destroy-me',$candidate->gid) }}"><i class="fa fa-fw fa-edit"></i> Remover</a>

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
