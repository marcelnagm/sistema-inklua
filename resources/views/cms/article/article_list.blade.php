@extends('layouts.cms')

@section('content')


    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Conteúdos externos</h1>

    @if($search_article || !(!$search_article && $articles->isEmpty()))
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <form action="{{ url("") }}/admin/artigos" method="GET" class="" id="form-search">
                    <div class="form-row mb-3">
                        <div class="col-lg-3">
                            <div class="input-group">                   
                                <input id="search_article" type="text" class="form-control bg-light border-1 small @error('search_article') is-invalid @enderror " name="search_article" value="{{ $search_article }}"  autocomplete="search_article" placeholder="Buscar artigo..." aria-label="Search" aria-describedby="basic-addon2">
                                @error('search_article')
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

    @if(!$articles->isEmpty())
  
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Lista de artigos</h6>
                    <a href="{{ url('') }}/admin/artigos/create" class="d-none d-sm-inline-block btn btn-lg btn-primary shadow-sm">Adicionar</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col">Título</th>
                                <th scope="col" style=" width: 100px;
                                text-align: right;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($articles as $article)
                                <tr>
                                    <td ><a href="{{ url("") }}/admin/artigos/{{ $article->id }}/edit">{{ $article->title }}</a></td>

                                    <td class="list-actions">                                   
                                        <a href="{{ url('') }}/admin/artigos/{{$article->id}}/edit" class="btn btn-sm btn-circle btn-primary"><i class="fas fa-pen"></i></a>
                                        <a class="delete-action btn btn-sm btn-circle btn-danger" data-toggle="modal" data-target="#deleteModal" data-title="{{$article->title}}" data-url-delete="/admin/artigos/{{$article->id}}"><i class="fas fa-trash"></i></a>
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
                    <a href="{{ url('') }}/admin/artigos/create" class="d-none d-sm-inline-block btn btn-lg btn-primary shadow-sm">Adicionar</a>
                </div>
            </div>
        </div>
        <div class="frame-wrap">
            {{$articles->appends(['search_article' => $search_article])->links()}}
        </div>
    @else
        <h2>Não há conteúdos externos cadastrados</h2>
        <a href="/admin/artigos/create" class="btn btn-primary">Adicionar Conteúdo externo</a>
    @endif
@endsection