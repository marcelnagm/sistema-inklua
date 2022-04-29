@extends('layouts.cms')

@section('content')


    <div class="row">

        <!-- Area Chart -->
        <div class="col-xl-12">
            <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Cadastro de grupo de vagas</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                @if( empty($group->id))
                    <form action="{{ url("") }}/admin/grupo/vagas" method="post" class="" enctype="multipart/form-data" id="form-group">
                @else
                    <form action="{{ url("") }}/admin/grupo/vagas/{{$group->id}}" method="post" class="" enctype="multipart/form-data"  id="form-group">
                    @method('PUT')
                @endif

                    @csrf
            
                    <div class="form-row mb-3">
                        <div class="col-lg-9">
                            <div class="form-group">
                                <label for="title" class="form-label">{{ __('Título') }}</label>
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ isset( $group->title ) && ( $group->title ) ? $group->title : old('title') }}"  autocomplete="title">
                                @error('title')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-row mb-3">
                        <div class="col-lg-9">
                            <div class="form-positionItens">
                                <label for="positionItens" class="form-label">{{ __('Escolha as vagas para esse grupo') }}</label>
                                <select class="form-control group-multiple @error('positionItens') is-invalid @enderror" name="positionItens[]" id="positionItens" multiple="multiple">
                                    @foreach ($positionItens as $content)
                                    <option value="{{ $content->id }}" @if(isset($relatedPositions))  {{ in_array($content->group_id, $relatedPositions) ? 'selected' : ''}}  @endif >{{ $content->compleo_code.' - '.$content->title  }}</option>
                                        
                                    @endforeach
                                </select>                              
                                @error('positionItens')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div> 
                        </div>
                    </div>

                    <div class="form-row mb-3">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="ordenation" class="form-label">{{ __('Prioridade') }}</label>
                                <input id="ordenation" type="text" class="form-control @error('ordenation') is-invalid @enderror" name="ordenation" value="{{ isset( $position->ordenation ) && ( $position->ordenation ) ? $position->ordenation : old('ordenation') }}"  autocomplete="ordenation">
                                @error('ordenation')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    @if( empty($group->id) )
                        <a href="{{ url('') }}/admin/grupo/vagas" class="btn btn-secondary">Voltar</a>
                        <button type="submit" class="btn btn-primary">Adicionar</button>
                    @else
                        <a href="{{ url('') }}/admin/grupo/vagas" class="btn btn-secondary">Voltar</a>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    @endif
                </form>
            </div>
            </div>
        </div>


    </div>
@endsection