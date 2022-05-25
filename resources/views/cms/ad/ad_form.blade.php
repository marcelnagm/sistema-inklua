@extends('layouts.cms')

@section('content')


<div class="row">

    <!-- Area Chart -->
    <div class="col-xl-12">
      <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Cadastro de Anúncio</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
            @if( empty($ad->id))
                <form action="{{ url("") }}/admin/anuncios" method="post" class="" enctype="multipart/form-data" id="form-ad">
            @else
                <form action="{{ url("") }}/admin/anuncios/{{$ad->id}}" method="post" class="" enctype="multipart/form-data"  id="form-ad">
                @method('PUT')
            @endif

            @csrf
        

            <div class="form-row mb-3">
                <div class="col-lg-9">
                    <div class="form-group">
                        <label for="title" class="form-label">Tipo</label>
                        <select name="category" class="form-control @error('category') is-invalid @enderror" id="">
                        <option value="" {{(!isset($ad) || $ad->category == "") ? "selected" : ""}}>Card de anúncio</option>
                            <option value="banner" {{(isset($ad) && $ad->category == "banner") ? "selected" : ""}}>Banner</option>
                        </select>
                        @error('category')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>


            <div class="form-row mb-3">
                <div class="col-lg-9">
                    <div class="form-group">
                        <label for="title" class="form-label">{{ __('Título') }}</label>
                        <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ isset( $ad->title ) && ( $ad->title ) ? $ad->title : old('title') }}"  autocomplete="title" >
                        @error('title')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            @if(isset($ad) && !empty($ad))
                <div class="form-row mb-3">
                    <div class="col-lg-1">
                        <div class="form-group">
                            <label for="coins" class="form-label">{{ __('Coins geradas') }}</label>
                            <input id="coins" type="text" class="form-control @error('coins') is-invalid @enderror" name="coins" value="{{ $ad->actions->sum('coins') }}"  autocomplete="coins" readonly>
                            @error('coins')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            @endif



            {{-- Upload de imagens --}}

            <div class="form-row mb-3">
                <div class="col-lg-12">
                    <label for="image_caption" class="form-label">Imagem </label>                                
                    <small>
                        <br>Banner: 696 x 1040px 
                        <br>Card de anúncio: 696 x 456px
                    </small>
                </div>
            </div>

            <div class="form-row mb-3">
                <div class="col-lg-6">
                    <div class="form-group">
                        <input id="image" type="file" data-file-caption-id="image_caption" class="form-control-file input-file-image @error('image') is-invalid @enderror" name="image" value="" autocomplete="image">
                        @error('image')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div> 
                </div>
                @if( isset($ad->image) && $ad->image != '' )
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

            <div id="img-preview-wrapper">
                @if( isset($ad->image) && $ad->image != '' )
                    <div class="form-row mb-3">
                        <div class="col-lg-12">
                            <img id="image-output" src="/storage/ads/{{$ad->image}}"  alt="" class="show-image">
                        </div>
                    </div>
                @endif
            </div>

            {{-- Fim upload de imagem--}}

            <div class="form-row mb-3">
                <div class="col-lg-9">
                    <div class="form-group">
                        <label for="description" class="form-label">{{ __('Descrição') }}</label>
                        <textarea id="description" cols="30" rows="10" class="form-control @error('description') is-invalid @enderror" name="description" autocomplete="description" >{{ (isset($ad->description)) ? $ad->description : old('description') }}</textarea>
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
                        <input id="url" type="text" class="form-control @error('url') is-invalid @enderror" name="url" value="{{ isset( $ad->url ) && ( $ad->url ) ? $ad->url : old('url') }}"  autocomplete="url" >
                        @error('url')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
                
            @if( empty($ad->id) )
                <a href="{{ url('') }}/admin/anuncios" class="btn btn-secondary">Voltar</a>
                <button type="submit" class="btn btn-primary">Adicionar</button>
            @else
                <a href="{{ url('') }}/admin/anuncios" class="btn btn-secondary">Voltar</a>
                <button type="submit" class="btn btn-primary">Salvar</button>
            @endif
            </form>
        </div>
      </div>
    </div>

   
  </div>
@endsection