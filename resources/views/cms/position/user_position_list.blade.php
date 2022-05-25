@extends('layouts.cms')

@section('content')


    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Vagas</h1>

    {{-- @if($search_position || !(!$search_position && $positions->isEmpty())) --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="form-row">
                    <form action="{{ url("") }}/admin/usuarios/vagas" method="GET" class="form-inline w-100 user-position-search" id="form-search">
                        <div class="col-lg-6 mb-3">
                            <div class="form-group">
                                <label for="status" class="form-label mr-4">{{ __('status') }}</label>
                                <select class="form-control @error('status') is-invalid @enderror" name="status" id="status">
                                    <option value="">Todos</option>                                   
                                    <option value="aguardando_aprovacao" {{($status == 'aguardando_aprovacao') ? 'selected' : ''}}>Aguardando Aprovação</option>                                   
                                    <option value="aguardando_pagamento" {{($status == 'aguardando_pagamento') ? 'selected' : ''}}>Aguardando Pagamento</option>                                   
                                    <option value="publicada" {{($status == 'publicada') ? 'selected' : ''}}>Publicada</option>                                   
                                    <option value="reprovada" {{($status == 'reprovada') ? 'selected' : ''}}>Reprovada</option>
                                </select>                              
                                @error('status')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="input-group">                   
                                <input id="search_position" type="text" class="form-control bg-light border-1 small @error('search_position') is-invalid @enderror " name="search_position" value="{{ $search_position }}"  autocomplete="search_position" placeholder="Buscar vaga..." aria-label="Search" aria-describedby="basic-addon2">
                                @error('search_position')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">{{__('Buscar')}}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    {{-- @endif --}}

    @if(!$positions->isEmpty())
  
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Lista de vagas</h6>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 100px;">Prioridade</th>
                                <th scope="col" style="width: 100px;">Grupo</th>
                                <th scope="col" style="width: 200px;">Status</th>
                                <th scope="col">Título</th>
                                <th scope="col" style=" width: 220px;
                                text-align: right;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($positions as $position)
                                    <tr>
                                        <td >
                                            <form action="{{ url("") }}/admin/usuarios/vagas/{{$position->id}}" method="post" id="form-ordenation">
                                                @method('PUT')
                                                @csrf
                                                <input type="hidden" name="nextUrl" value="{{ url()->full() }}">
                                                <input type="text" name='ordenation' value="{{ $position->ordenation }} " class="form-control w-100">
                                            </form>
                                        </td>
                                        <td ><a href="{{ url("") }}/admin/usuarios/vagas/{{ $position->id }}/admin/grupo/vagas/{{ $position->group_id }}">{{ $position->group_id }}</a></td>
                                        <td ><a href="{{ url("") }}/admin/usuarios/vagas/{{ $position->id }}/edit">{{ $position->getStatusName() }}</a></td>
                                        <td ><a href="{{ url("") }}/admin/usuarios/vagas/{{ $position->id }}/edit">{{ $position->title }}</a></td>
                                        
                                        <td class="list-actions">
                                            <a href="{{ url('') }}/admin/usuarios/vagas/{{$position->id}}/edit" class="btn btn-sm btn-circle btn-primary">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <button type="submit" form="form-ordenation" value="ordenation" class="btn btn-primary">Salvar prioridade</button>
                                        </td>
                                    </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer py-3">
                <div class="d-sm-flex align-items-end justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"></h6>
                </div>
            </div>
        </div>
        <div class="frame-wrap">
            {{$positions->appends(['search_position' => $search_position])->links()}}
        </div>
    @else
        <h2>vagas não encontradas</h2>
    @endif
@endsection