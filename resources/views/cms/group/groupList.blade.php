@extends('layouts.cms')

@section('content')


    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Grupo de vagas</h1>

    @if($search_group || !(!$search_group && $groups->isEmpty()))
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <form action="{{ url("") }}/admin/grupo/vagas" method="GET" class="" id="form-search">
                    <div class="form-row mb-3">
                        <div class="col-lg-3">
                            <div class="input-group">                   
                                <input id="search_group" type="text" class="form-control bg-light border-1 small @error('search_group') is-invalid @enderror " name="search_group" value="{{ $search_group }}"  autocomplete="search_group" placeholder="Buscar grupo..." aria-label="Search" aria-describedby="basic-addon2">
                                @error('search_group')
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

    @if(!$groups->isEmpty())

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Lista de grupos de vagas</h6>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 100px;">id</th>
                                <th scope="col">Título</th>
                                <th scope="col" style=" width: 100px;
                                text-align: right;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($groups as $group)
                                <tr>
                                    <td ><a href="{{ url("") }}/admin/grupo/vagas/{{ $group->id }}/edit">{{ $group->id }}</a></td>
                                    <td ><a href="{{ url("") }}/admin/grupo/vagas/{{ $group->id }}/edit">{{ $group->title }}</a></td>

                                    <td class="list-actions">
                                        <a href="{{ url('') }}/admin/grupo/vagas/{{$group->id}}/edit" class="btn btn-sm btn-circle btn-primary">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <a class="delete-action btn btn-sm btn-circle btn-danger" data-toggle="modal" data-target="#deleteModal" data-title="{{$group->title}}" data-url-delete="/admin/grupo/vagas/{{$group->id}}"><i class="fas fa-trash"></i></a>
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
            {{$groups->appends(['search_group' => $search_group])->links()}}
        </div>
    @else
        <h2>Grupos de vagas não encontradas</h2>
    @endif
@endsection