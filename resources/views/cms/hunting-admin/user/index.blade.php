@extends('layouts.cms')

@section('template_title')
User
@endsection

@section('content')
<div class="col-xl-12">
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h5 class="m-0 font-weight-bold text-primary">Lista de Recrutadores - Hunting
            </h5>
        </div>  
                <div class="card-body">
                    <div class="ml-4">
                    <button class="btn btn-primary " onclick="$('#filtros').toggle('100');"> Filtros</button>
                    </div>    
                
                    @if(Session::has('recruiter'))

                    <span class="alert danger"> Resultado Filtrado <a href="{{ route('users.clear') }}"class="btn btn-danger btn-sm" >Limpar Filtro</a></span>
                    @endif
                 <div  id="filtros" style="display: none;">                       
                        <form method="post" action="{{ route('users.search') }}"  role="form" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group form-control-lg" >
                                <table>
                                    <thead>
                                    <th colspan="2">

                                    </th>                         
                                    </thead>
                                    <tr>
                                        <td>
                                            {{ Form::label('Pesquisar por:') }}
                                        </td>
                                        @if(Session::has('recruiter'))
                                        <td>
                                            {{ Form::text('search', Session::get('recruiter')['param'],['class' => 'form-control','placeholder' => 'Nome, telefone ou email' ]) }}                            
                                        </td>                                                                                
                                        @else
                                        <td>
                                            {{ Form::text('search', '',['class' => '','placeholder' => 'Nome, telefone ou código' ]) }}                            
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
                                    <th>No</th>
                                    <th>Nome</th>
                                    <th>Sobrenome</th>
                                    <th>CNPJ</th>
                                    <th>Nome Fantasia</th>								
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                <tr>
                                    <td>INKLUER#{{ $user->id }}</td>

                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->lastname }}</td>
                                    <td>{{ $user->cnpj }}</td>
                                    <td>{{ $user->fantasy_name }}</td>										
                                    <td>
                                            <a class="btn btn-sm btn-primary " href="{{ route('users.show',$user->id) }}"><i class="fa fa-fw fa-eye"></i> Exibir</a>                                            
                                        
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {!! $users->links() !!}
        </div>
@endsection
