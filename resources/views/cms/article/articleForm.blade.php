@extends('layouts.cms')

@section('content')


<div class="row">

    <!-- Area Chart -->
    <div class="col-xl-12">
      <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Cadastro de Conteúdo externo</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
            @if( empty($article->id))
                <form action="{{ url("") }}/admin/artigos" method="post" class="" enctype="multipart/form-data" id="form-article">
            @else
                <form action="{{ url("") }}/admin/artigos/{{$article->id}}" method="post" class="" enctype="multipart/form-data"  id="form-article">
                @method('PUT')
            @endif

            @csrf
        
            <div class="form-row mb-3">
                <div class="col-lg-9">
                    <div class="form-group">
                        <label for="title" class="form-label">{{ __('Título') }}</label>
                        <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ isset( $article->title ) && ( $article->title ) ? $article->title : old('title') }}"  autocomplete="title" >
                        @error('title')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-row mb-3">
                <div class="col-lg-9">
                    <div class="form-group">
                        <label for="category" class="form-label">{{ __('Categoria') }}</label>
                        <input id="category" type="text" class="form-control @error('category') is-invalid @enderror" name="category" value="{{ isset( $article->category ) && ( $article->category ) ? $article->category : old('category') }}"  autocomplete="category" >
                        @error('category')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>



            {{-- Upload de imagens --}}

            <div class="form-row mb-3">
                <div class="col-lg-12">
                    <label for="image_caption" class="form-label">{{ __('Imagem') }}</label> 
                    <small>
                        <br>Tamanho da imagem: 696 x 344px
                    </small>                              
                </div>
            </div>

            <div class="form-row mb-3">
                <div class="col-lg-6">
                    <div class="form-group">
                        <input id="image" type="file" data-file-caption-id="image_caption" class="form-control-file input-file-image @error('image') is-invalid @enderror" name="image" value="{{ isset($article->image) }}" autocomplete="image">
                        @error('image')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div> 
                </div>
                @if( isset($article->image) && $article->image != '')
                    <div class="col-lg-6">
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" value="1" name="remove_imagem" class="custom-control-input select-remove" id="remove_imagem"  data-file-input="image" >
                                <label for="remove_imagem" class="custom-control-label">{{ __('Remover imagem') }}</label>
                            </div>
                            @error('remove_imagem')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                @endif    
            </div>

            @if( isset($article->image) && $article->image != '' )
            <div id="img-preview-wrapper">
                <div class="form-row mb-3">
                    <div class="col-lg-12">
                        <img id="image-output" src="/storage/articles/{{$article->image}}" alt="" class="show-image">                                
                    </div>
                </div>
            </div>
            @endif


            {{-- <div class="form-row mb-3" id="image_caption" @if( !$article->image ) hidden @endif>
                <div class="col-lg-12">
                    <div class="form-group" >
                        <label for="image_caption" class="form-label">{{ __('Legenda') }}</label>
                        <input id="image_caption" type="text" class="form-control @error('image_caption') is-invalid @enderror" name="image_caption" value="{{ $article->image_caption }}" autocomplete="image_caption" >
                        @error('image_caption')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div> 
                </div>
            </div> --}}


            {{-- Fim upload de imagem--}}

            <div class="form-row mb-3">
                <div class="col-lg-9">
                    <div class="form-group">
                        <label for="description" class="form-label">{{ __('Descrição') }}</label>
                        <textarea id="description" cols="30" rows="10" class="form-control @error('description') is-invalid @enderror" name="description" autocomplete="description" >{{ (isset($article->description)) ? $article->description : old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-row mb-3">
                <div class="col-lg-9">
                    <div class="form-group">
                        <label for="url" class="form-label">{{ __('URL') }}</label>
                        <input id="url" type="text" class="form-control @error('url') is-invalid @enderror" name="url" value="{{ isset( $article->url ) && ( $article->url ) ? $article->url : old('url') }}"  autocomplete="url" >
                        @error('url')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-row mb-3">
                <div class="col-lg-9">
                    <div class="form-group">
                        <label for="source" class="form-label">{{ __('Fonte') }}</label>
                        <input id="source" type="text" class="form-control @error('source') is-invalid @enderror" name="source" value="{{ isset( $article->source ) && ( $article->source ) ? $article->source : old('source') }}"  autocomplete="source" >
                        @error('source')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
                
            @if( empty($article->id) )
                <a href="{{ url('') }}/admin/artigos" class="btn btn-secondary">Voltar</a>
                <button type="submit" class="btn btn-primary">Adicionar</button>
            @else
                <a href="{{ url('') }}/admin/artigos" class="btn btn-secondary">Voltar</a>
                <button type="submit" class="btn btn-primary">Salvar</button>
            @endif
            </form>
        </div>
      </div>
    </div>

   
  </div>
@endsection