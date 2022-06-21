@extends('layouts.cms')

@section('content')


<div class="row">

    <!-- Area Chart -->
    <div class="col-xl-12">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Edição de vagas internas</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                @if( empty($position->id))
                <h2>Não há vagas</h2>
                @else
                <form action="{{ url("") }}/admin/vagas/{{$position->id}}" method="post" class="" enctype="multipart/form-data"  id="form-position">
                    @method('PUT')
                    @endif

                    @csrf

                    <div class="form-row mb-3">
                        <div class="col-lg-9">
                            <div class="form-group">
                                <label for="title" class="form-label">{{ __('Título') }}</label>
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ isset( $position->title ) && ( $position->title ) ? $position->title : old('title') }}"  autocomplete="title" readonly>
                                @error('title')
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
                                <br>Tamanho da imagem: 600 x 290px
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
                        @if( isset($position->image) && $position->image != '')
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" value="1" name="remove_imagem" class="custom-control-input select-remove" id="remove_imagem"  data-file-input="image">
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
                        @if( isset($position->image) && $position->image != '' )
                        <label for="image-output" class="form-label">Preview de imagem</label>
                        <div class="form-row mb-3">
                            <div class="col-lg-12">
                                <img id="image-output" src="/storage/positions/{{$position->image}}"  alt="" class="show-image">
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Fim upload de imagem--}}
                    <div class="form-row mb-3">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="compleo_code" class="form-label">{{ __('compleo_code') }}</label>
                                <input id="compleo_code" type="text" class="form-control @error('compleo_code') is-invalid @enderror" name="compleo_code" value="{{ isset( $position->compleo_code ) && ( $position->compleo_code ) ? $position->compleo_code : old('compleo_code') }}"  autocomplete="compleo_code" readonly>
                                @error('compleo_code')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-row mb-3">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="city" class="form-label">{{ __('Cidade') }}</label>
                                <input id="city" type="text" class="form-control @error('city') is-invalid @enderror" name="city" value="{{ isset($position->city) && ($position->city) ? $position->city : old('city') }}"  autocomplete="city" readonly>
                                @error('city')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="state" class="form-label">{{ __('Estado') }}</label>
                                <input id="state" type="text" class="form-control @error('state') is-invalid @enderror" name="state" value="{{ isset($position->state) && ($position->state) ? $position->state : old('state') }}"  autocomplete="state" readonly>                        
                                @error('state')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div> 
                        </div>
                    </div>

                    <div class="form-row mb-3">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="branch_code" class="form-label">{{ __('branch_code') }}</label>
                                <input id="branch_code" type="text" class="form-control @error('branch_code') is-invalid @enderror" name="branch_code" value="{{ isset($position->branch_code) && ($position->branch_code) ? $position->branch_code : old('branch_code') }}"  autocomplete="branch_code" readonly>
                                @error('branch_code')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="branch_name" class="form-label">{{ __('branch_name') }}</label>
                                <input id="branch_name" type="text" class="form-control @error('branch_name') is-invalid @enderror" name="branch_name" value="{{ isset($position->branch_name) && ($position->branch_name) ? $position->branch_name : old('branch_name') }}"  autocomplete="branch_name" readonly>                        
                                @error('branch_name')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div> 
                        </div>
                    </div>

                    <hr>

                    <div class="form-row mb-3">
                        <div class="col-lg-9">
                            <div class="form-group">
                                <label for="description" class="form-label">{{ __('Descrição') }}</label>
                                @php $description = (isset($position->description)) ? $position->description : old('description') @endphp
                                {!! $description !!}
                                @error('description')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>

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
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="group_id" class="form-label">{{ __('Grupo') }}</label>
                                <select class="form-control @error('group_id') is-invalid @enderror" name="group_id" id="group_id">
                                    <option value="">Adicionar a um grupo</option>
                                    @foreach ($groups as $group)
                                    <option value="{{ $group->id }}" {{ ($position->group_id == $group->id) ? 'selected' : ''}} >{{ $group->title }}</option>

                                    @endforeach
                                </select>                              
                                @error('group')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div> 
                        </div>
                    </div>

                    <a href="{{ url('') }}/admin/vagas" class="btn btn-secondary">Voltar</a>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                      @if($position->status == 'aguardando_aprovacao')
                    <a href="{{route('interno.aprovar',$position->id)}}?status=publicada" class="btn btn-success user-position-approve">Aprovar</a>
                    <a href="{{route('interno.aprovar',$position->id)}}?status=reprovada" class="btn btn-danger user-position-reprove">Reprovar</a>
                     @endif
                    @if($position->status == 'publicada')
                    <a href="{{route('interno.aprovar',$position->id)}}?status=fechada" class="btn btn-dark user-position-approve">Fechar Vaga</a>
                    @endif

                </form>
            </div>
        </div>
    </div>
</div>
@endsection