@extends('layouts.cms')

@section('content')

<!--externos-->
<div class="row">
    <!-- Area Chart -->
    <div class="col-xl-12">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Edição de vagas externas</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                @if( empty($position->id))
                <h2>Não há vagas</h2>
                @else
                <form action="{{ url("") }}/admin/usuarios/vagas/{{$position->id}}" method="post" class="user-position-form" enctype="multipart/form-data"  id="form-position">
                    @method('PUT')
                    @endif
                    @if ($errors->any())
                    @foreach ($errors->all() as $error)
                    <div>{{$error}}</div>
                    @endforeach
                    @endif
                    @csrf

                    <div class="col-lg-12">
                        <div class="form-row mb-3">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="title" class="form-label">{{ __('Título') }}</label>
                                    <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ isset( $position->title ) && ( $position->title ) ? $position->title : old('title') }}"  autocomplete="title" >
                                    @error('title')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="salary" class="form-label">{{ __('Remuneração') }}</label>
                                    <input id="salary" type="text" class="form-control @error('salary') is-invalid @enderror" name="salary" value="{{ isset( $position->salary ) && ( $position->salary ) ? $position->salary : old('salary') }}"  autocomplete="salary" >
                                    @error('salary')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>                       
                    </div>




                    {{-- Upload de imagens --}}
                    <div class="col-lg-12">
                        <div class="form-row mb-3">
                            <div class="col-lg-9">
                                <label for="image_caption" class="form-label">{{ __('Imagem') }}</label>
                                <small>
                                    <br>Tamanho da imagem: 600 x 290px
                                </small>      
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-lg-9">
                                <div class="form-group">
                                    <input id="image" type="file" data-file-caption-id="image_caption" class="form-control-file input-file-image @error('image') is-invalid @enderror" name="image" value="" autocomplete="image">
                                    @error('image')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                    @enderror
                                </div> 
                            </div>
                            @if( isset($position->image) && $position->image != '')
                            <div class="col-lg-12">
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
                    </div>

                    {{-- Fim upload de imagem--}}
                    <div class="form-row mb-3">
                        <<div class=" col-lg-3 mb-3">
                            <div class="form-group ">
                                {{ Form::label('Modalidade Remoto') }}
                                <input type="hidden" name="remote" value="0">
                                <input name="remote" type="checkbox" class="" @if($position->remote==1) checked @endif value="1">                        
                                {!! $errors->first('remote', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        </div>
                        <div class="col-lg-3  mb-3">
                            <div class="form-group ">
                                {{ Form::label('Modalidade Presencial')}}
                                <input type="hidden" name="presential" value="0">
                                <input name="presential" type="checkbox" class="" @if($position->presential==1) checked @endif value="1">                        
                                {!! $errors->first('presential', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        </div>
                        <div class="col-lg-3  mb-3">
                            <div class="form-group ">
                                {{ Form::label('Modalidade Híbrida') }}
                                <input type="hidden" name="hybrid" value="0">
                                <input name="hybrid" type="checkbox" class="" @if($position->hybrid==1) checked @endif value="1">                        
                                {!! $errors->first('hybrid', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-row mb-3">                                                                        
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="hours" class="form-label">{{ __('Horário') }}</label>
                                    <input id="hours" type="text" class="form-control @error('hours') is-invalid @enderror" name="hours" value="{{ isset( $position->hours ) && ( $position->hours ) ? $position->hours : old('hours') }}"  autocomplete="compleo_code" >
                                    @error('hours')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        
                        </div>

                        <hr>
                        <div class="form-row mb-3">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="state" class="form-label">{{ __('Estado') }}</label>
                                    <select class="form-control @error('state') is-invalid @enderror" name="state" id="state">
                                        <option value="" disabled {{ ($position->state== '') ? 'selected' : '' }}>Selecione um estado</option>
                                        <option value="AC" {{ ($position->state== 'AC') ? 'selected' : '' }}>Acre</option>
                                        <option value="AL" {{ ($position->state== 'AL') ? 'selected' : '' }}>Alagoas</option>
                                        <option value="AP" {{ ($position->state== 'AP') ? 'selected' : '' }}>Amapá</option>
                                        <option value="AM" {{ ($position->state== 'AM') ? 'selected' : '' }}>Amazonas</option>
                                        <option value="BA" {{ ($position->state== 'BA') ? 'selected' : '' }}>Bahia</option>
                                        <option value="CE" {{ ($position->state== 'CE') ? 'selected' : '' }}>Ceará</option>
                                        <option value="DF" {{ ($position->state== 'DF') ? 'selected' : '' }}>Distrito Federal</option>
                                        <option value="ES" {{ ($position->state== 'ES') ? 'selected' : '' }}>Espírito Santo</option>
                                        <option value="GO" {{ ($position->state== 'GO') ? 'selected' : '' }}>Goiás</option>
                                        <option value="MA" {{ ($position->state== 'MA') ? 'selected' : '' }}>Maranhão</option>
                                        <option value="MT" {{ ($position->state== 'MT') ? 'selected' : '' }}>Mato Grosso</option>
                                        <option value="MS" {{ ($position->state== 'MS') ? 'selected' : '' }}>Mato Grosso do Sul</option>
                                        <option value="MG" {{ ($position->state== 'MG') ? 'selected' : '' }}>Minas Gerais</option>
                                        <option value="PA" {{ ($position->state== 'PA') ? 'selected' : '' }}>Pará</option>
                                        <option value="PB" {{ ($position->state== 'PB') ? 'selected' : '' }}>Paraíba</option>
                                        <option value="PR" {{ ($position->state== 'PR') ? 'selected' : '' }}>Paraná</option>
                                        <option value="PE" {{ ($position->state== 'PE') ? 'selected' : '' }}>Pernambuco</option>
                                        <option value="PI" {{ ($position->state== 'PI') ? 'selected' : '' }}>Piauí</option>
                                        <option value="RJ" {{ ($position->state== 'RJ') ? 'selected' : '' }}>Rio de Janeiro</option>
                                        <option value="RN" {{ ($position->state== 'RN') ? 'selected' : '' }}>Rio Grande do Norte</option>
                                        <option value="RS" {{ ($position->state== 'RS') ? 'selected' : '' }}>Rio Grande do Sul</option>
                                        <option value="RO" {{ ($position->state== 'RO') ? 'selected' : '' }}>Rondônia</option>
                                        <option value="RR" {{ ($position->state== 'RR') ? 'selected' : '' }}>Roraima</option>
                                        <option value="SC" {{ ($position->state== 'SC') ? 'selected' : '' }}>Santa Catarina</option>
                                        <option value="SP" {{ ($position->state== 'SP') ? 'selected' : '' }}>São Paulo</option>
                                        <option value="SE" {{ ($position->state== 'SE') ? 'selected' : '' }}>Sergipe</option>
                                        <option value="TO" {{ ($position->state== 'TO') ? 'selected' : '' }}>Tocantins</option>
                                    </select> 
                                    @error('state')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                    @enderror
                                </div> 
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="city" class="form-label">{{ __('Cidade') }}</label>
                                    <input id="city" type="text" class="form-control @error('city') is-invalid @enderror" name="city" value="{{ isset($position->city) && ($position->city) ? $position->city : old('city') }}"   >
                                    @error('city')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="district" class="form-label">{{ __('Bairro') }}</label>
                                    <input id="district" type="text" class="form-control @error('district') is-invalid @enderror" name="district" value="{{ isset($position->district) && ($position->district) ? $position->district : old('district') }}"  >
                                    @error('district')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <hr>


                        <div class="form-row mb-3">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="description" class="form-label">{{ __('Descrição') }}</label>
                                    <textarea id="descri" cols="30" rows="10" class="form-control @error('description') is-invalid @enderror" name="description" autocomplete="description">{{ (isset($position->description)) ? $position->description : old('description') }}</textarea>
                                    @error('description')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="benefits" class="form-label">{{ __('Descrição dos benefícios') }}</label>
                                    {{ Form::textarea('benefits', (isset($position->benefits)) ? $position->benefits : old('benefits'), ['class' => 'form-control' . ($errors->has('benefits') ? ' is-invalid' : ''), 'placeholder' => 'Benficios']) }}
                                    {!! $errors->first('benefits', '<div class="invalid-feedback">:message</div>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-row mb-3">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="requirements" class="form-label">{{ __('Descrição dos Requisitos') }}</label>
                                    {{ Form::textarea('requirements', (isset($position->requirements)) ? $position->requirements : old('requirements'), ['class' => 'form-control' . ($errors->has('requirements') ? ' is-invalid' : ''), 'placeholder' => 'requirements']) }}
                                    {!! $errors->first('requirements', '<div class="invalid-feedback">:message</div>') !!}
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-row mb-3">
                            <div class="col-lg-6">
                                <div class="form-group">         {{ Form::label('Nível de Inglês') }}
                                    @include('layouts.partials.select',array('list' => $english_levels,'param' => $position->english_level,'name' => 'english_level'))

                                    {!! $errors->first('english_level', '<div class="invalid-feedback">:message</div>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-row mb-3">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="remote" class="form-label">{{ __('Candidatura') }}</label>
                                    <div class="col-lg-3 custom-control custom-switch">
                                        <input name="application_type" type="radio" class="form-control-sm" @if($position->application_type=='email') checked @endif value="email">  Email                      
                                    </div>

                                    <div class="col-lg-3 custom-control custom-switch">
                                        <input name="application_type" type="radio" class="form-control-sm" @if($position->application_type=='url') checked @endif value="url">  Link Externo                     
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="application" class="form-label">{{ __('Direcionamento') }}</label>
                                    <input id="application" type="text" class="form-control @error('application') is-invalid @enderror" name="application" value="{{ isset( $position->application ) && ( $position->application ) ? $position->application : old('application') }}">
                                    @error('application')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-lg-9">

                            </div>
                        </div>

                        <div class="form-row">

                            <div class="col-lg-6">
                                {{-- Preview de imagens --}}
                                <div id="img-preview-wrapper">
                                    @if( isset($position->image) && $position->image != '' )
                                    <label for="image-output" class="form-label">Preview de imagem</label>
                                    <div class="form-row mb-3">
                                        <div class="col-lg-12">
                                            <img id="image-output" src="{{$position->image}}"  alt="" class="show-image">
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                {{-- Fim Preview de imagem--}}
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

                        <div class="form-row mb-3">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="recruiter" class="form-label">{{ __('Nome do Recrutador') }}</label>
                                    <input id="recruiter" type="text" class="form-control @error('ordenation') is-invalid @enderror" name="recruiter" value="{{  $position->user() !== null ? $position->user()->first()->fullname() : 'Nao definido' }}"  readonly>
                                    @error('ordenation')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="recruiter" class="form-label">{{ __('Email do Recrutador') }}</label>
                                    <input id="recruiter" type="text" class="form-control @error('ordenation') is-invalid @enderror" name="recruiter_email" value="{{  $position->user()!== null   ? $position->user()->first()->email : 'Nao definido' }}"  readonly>
                                    @error('ordenation')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-row mb-3">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="recruiter" class="form-label">{{ __('Nome da Empresa') }}</label>
                                    <input id="recruiter" type="text" class="form-control @error('ordenation') is-invalid @enderror" name="recruiter" value="{{  $position->user() !== null ? $position->user()->first()->fantasy_name : 'Nao definido' }}"  readonly>
                                    @error('ordenation')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="recruiter" class="form-label">{{ __('CNPJ') }}</label>
                                    <input id="recruiter" type="text" class="form-control @error('ordenation') is-invalid @enderror" name="recruiter_email" value="{{  $position->user()!== null   ? $position->user()->first()->corporate_name : 'Nao definido' }}"  readonly>
                                    @error('ordenation')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="recruiter" class="form-label">{{ __('Email do Recrutador') }}</label>
                                    <input id="recruiter" type="text" class="form-control @error('ordenation') is-invalid @enderror" name="recruiter_email" value="{{  $position->user()!== null   ? $position->user()->first()->cnpj : 'Nao definido' }}"  readonly>
                                    @error('ordenation')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                              <div class="form-row mb-3">
 <<div class=" col-lg-7 mb-3">
                            <div class="form-group ">
                                {{ Form::label('Já foi encaminhado parta o faturamento') }}
                                <input type="hidden" name="billed" value="0">
                                <input name="billed" type="checkbox" class="" @if($position->billed==1) checked @endif value="1">                        
                                {!! $errors->first('remote', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        </div>
                        </div>
                        
                        <div class="form-row mb-3">
                            <div class="col-lg-9">
                                <div class="form-group">
                                    <label for="status" class="form-label">{{ __('Status: ') }} @if($position->user_obj()->first_position())Essa é a primeira vaga do CNPJ @endif </label>
                                    {!! $position->getStatusName() !!}
                                    <select name="status" class="form-control"         >
                                        @foreach ($status as $key => $value)
                                        <option value="{{ $key }}"
                                                @if ($key ==  $position->status)   selected="selected"     @endif
                                            > {{$value }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                        </div>

                        <a href="{{ url('') }}/admin/usuarios/vagas" class="btn btn-secondary">Voltar</a>
                        @if($position->status == 'aguardando_aprovacao')
                        <a href="{{route('externo.aprovar',$position->id)}}?status=aguardando_pagamento" class="btn btn-success user-position-approve">Aprovar</a>
                        <a href="{{route('externo.aprovar',$position->id)}}?status=reprovada" class="btn btn-danger user-position-reprove">Reprovar</a>
                        @else
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection