@extends('layouts.cms')

@section('content')

<!--interna-->
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
                                            <option value="AC" {{ ($position->state == 'AC') ? 'selected' : '' }}>Acre</option>
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
                        <div class="col-lg-4 float-right">
                            <div class="form-group">
                                <label for="city" class="form-label">{{ __('Cidade') }}</label>
                                <input id="city" type="text" class="form-control @error('city') is-invalid @enderror" name="city" value="{{ isset($position->city) && ($position->city) ? $position->city : old('city') }}"   >
                                @error('city')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4 float-right">
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
                                <label for="description" class="form-label">{{ __('Descrição geral da vaga') }}</label>
                                {{ Form::textarea('description', (isset($position->description)) ? $position->description : old('description'), ['class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''), 'placeholder' => 'Descricao']) }}
                                {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
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
                    <div class="form-row mb-3 hidden" @if($position->in_compleo == 0)style="display: none;" @endif>
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



                    <div class="form-row mb-3 " @if($position->in_compleo == 0)style="display: none;" @endif>
                        <div class="col-lg-3">
                            <div class="form-group hidden">
                                <label for="branch_code" class="form-label">{{ __('branch_code') }}</label>
                                <input id="branch_code" type="text" class="form-control @error('branch_code') is-invalid @enderror" name="branch_code" value="{{ isset($position->branch_code) && ($position->branch_code) ? $position->branch_code : old('branch_code') }}"  autocomplete="branch_code" readonly>
                                @error('branch_code')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3" @if($position->in_compleo == 0)style="display: none;" @endif >
                            <div class="form-group ">
                                <label for="branch_name" class="form-label">{{ __('branch_name') }}</label>
                                <input id="branch_name" type="text" class="form-control @error('branch_name') is-invalid @enderror" name="branch_name" value="{{ isset($position->branch_name) && ($position->branch_name) ? $position->branch_name : old('branch_name') }}"  autocomplete="branch_name" readonly>                        
                                @error('branch_name')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div> 
                        </div>
                    </div>

                    <hr>
                  
                    @isset($contentclient)
                  
                    <div class="form-row mb-3">
                        <div class="col-lg-3" >
                            <div class="form-group ">
                                <label for="vacancy" class="form-label">{{ __('Quantidade de Posições') }}</label>
                                <input id="vacancy" type="text" class="form-control @error('vacancy') is-invalid @enderror" name="vacancy" value="{{ isset($contentclient->vacancy) && ($contentclient->vacancy) ? $contentclient->vacancy : old('vacancy') }}"  readonly>                        
                                @error('vacancy')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div> 
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">    
                                {{ Form::label('Cliente') }}
                                @include('layouts.partials.select',array('list' => $clients,'param' => $contentclient->client_id,'name' => 'client_id','readonly' => 'readonly'))

                                {!! $errors->first('english_level', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">    
                                {{ Form::label('Condições do Cliente') }}
                                @include('layouts.partials.select',array('list' => $conditions,'param' => $contentclient->client_condition_id,'name' => 'client_condition_id','readonly' => 'readonly'))

                                {!! $errors->first('english_level', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        </div>
                       
                    
                    </div>
                    @endisset
                    <hr>

                    <div class="form-row mb-3">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="observation" class="form-label">{{ __('Observações da vaga[VISIVEL APENAS AO RECRUTADOR]') }}</label>
                                {{ Form::textarea('observation', (isset($position->observation)) ? $position->observation : old('observation'), ['class' => 'form-control' . ($errors->has('observation') ? ' is-invalid' : ''), 'placeholder' => 'Descricao']) }}
                                {!! $errors->first('observation', '<div class="invalid-feedback">:message</div>') !!}
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
                    <hr>                    
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
            </div>
            <div class="col-lg-12 mb-4">

                <a href="{{ url('') }}/admin/vagas" class="btn btn-secondary">Voltar</a>
                <button type="submit" class="btn btn-primary">Salvar</button>
                @if($position->status == 'aguardando_aprovacao')
                <a href="{{route('interno.aprovar',$position->id)}}?status=publicada" class="btn btn-success user-position-approve">Aprovar</a>
                <a href="{{route('interno.aprovar',$position->id)}}?status=reprovada" class="btn btn-danger user-position-reprove">Reprovar</a>
                @endif
                @if($position->status == 'publicada')
                <a href="{{route('interno.aprovar',$position->id)}}?status=fechada" class="btn btn-dark user-position-approve">Fechar Vaga</a>
                @endif
            </div>

            </form>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
@endsection