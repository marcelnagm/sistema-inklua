@extends('layouts.cms')

@section('content')


    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Usuários</h1>

    @if($search_user || !(!$search_user && $users->isEmpty()))
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <form action="{{ url("") }}/admin/usuarios" method="GET" class="" id="form-search">
                    <div class="form-row mb-3">
                        <div class="col-lg-3">
                            <div class="input-group">                   
                                <input id="search_user" type="text" class="form-control bg-light border-1 small @error('search_user') is-invalid @enderror " name="search_user" value="{{ $search_user }}"  autocomplete="search_user" placeholder="Buscar usuário..." aria-label="Search" aria-describedby="basic-addon2">
                                @error('search_user')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">{{__('Buscar')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if(!$users->isEmpty())
  
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Lista de Usuários</h6>
                    <a href="{{ url('') }}/admin/usuarios/create" class="d-none d-sm-inline-block btn btn-lg btn-primary shadow-sm">Adicionar</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col" style=" width: 100px;">id</th>
                                <th scope="col">Nome</th>
                                <th scope="col">E-mail</th>
                                <th scope="col" style=" width: 100px;
                                text-align: right;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td ><a href="{{ url("") }}/admin/usuarios/{{ $user->id }}/edit">{{ $user->id }}</a></td>
                                    <td ><a href="{{ url("") }}/admin/usuarios/{{ $user->id }}/edit">{{ $user->name }}</a></td>
                                    <td ><a href="{{ url("") }}/admin/usuarios/{{ $user->id }}/edit">{{ $user->email }}</a></td>

                                    <td class="list-actions">
                                        <a href="{{ url('') }}/admin/usuarios/{{$user->id}}/edit" class="btn btn-sm btn-circle btn-primary"><i class="fas fa-pen"></i></a>
                                        <a class="delete-action btn btn-sm btn-circle btn-danger" data-toggle="modal" data-target="#deleteModal" data-title="{{$user->name}}" data-url-delete="/admin/usuarios/{{$user->id}}"><i class="fas fa-trash"></i></a>
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
                    <a href="{{ url('') }}/admin/usuarios/create" class="d-none d-sm-inline-block btn btn-lg btn-primary shadow-sm">Adicionar</a>
                </div>
            </div>
        </div>
        <div class="frame-wrap">
            {{$users->appends(['search_user' => $search_user])->links()}}
        </div>
    @else
        <h2>Não há Usuários cadastrados</h2>
        <a href="/admin/usuarios/create" class="btn btn-primary">Adicionar usuário</a>
    @endif
@endsection