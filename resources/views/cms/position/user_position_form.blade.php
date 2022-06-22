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

                    <div class="form-row mb-3">
                        <div class="col-lg-9">
                            <div class="form-group">
                                <label for="title" class="form-label">{{ __('Título') }}</label>
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ isset( $position->title ) && ( $position->title ) ? $position->title : old('title') }}"  autocomplete="title" >
                                @error('title')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-lg-9">
                            <div class="form-group">
                                <label for="application" class="form-label">{{ __('Aplicação') }}</label>
                                <input id="application" type="text" class="form-control @error('application') is-invalid @enderror" name="application" value="{{ isset( $position->application ) && ( $position->application ) ? $position->application : old('application') }}">
                                @error('application')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-lg-6">
                            <div class="form-row">
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label for="contract_type" class="form-label">{{ __('Contrato') }}</label>
                                        <select class="form-control @error('contract_type') is-invalid @enderror" name="contract_type" id="contract_type">
                                            <option value="presencial" {{($position->contract_type == 'presencial') ? 'selected': ''}}>presencial</option>
                                            <option value="remoto" {{($position->contract_type == 'remoto') ? 'selected': ''}}>remoto</option>
                                        </select>                              
                                        @error('group')
                                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                        @enderror
                                    </div> 
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label for="salary" class="form-label">{{ __('Salário') }}</label>
                                        <input id="salary" type="text" class="form-control @error('salary') is-invalid @enderror" name="salary" value="{{ isset( $position->salary ) && ( $position->salary ) ? $position->salary : old('salary') }}" >
                                        @error('salary')
                                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label for="city" class="form-label">{{ __('Cidade') }}</label>
                                        <input id="city" type="text" class="form-control @error('city') is-invalid @enderror" name="city" value="{{ isset($position->city) && ($position->city) ? $position->city : old('city') }}"  autocomplete="city" >
                                        @error('city')
                                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label for="state" class="form-label">{{ __('Estado') }}</label>
                                        <select class="form-control @error('state') is-invalid @enderror" name="state" id="state">
                                            <option value="" disabled {{ (old('state') == '') ? 'selected' : '' }}>Selecione um estado</option>
                                            <option value="AC" {{ (old('state') == 'AC') ? 'selected' : '' }}>Acre</option>
                                            <option value="AL" {{ (old('state') == 'AL') ? 'selected' : '' }}>Alagoas</option>
                                            <option value="AP" {{ (old('state') == 'AP') ? 'selected' : '' }}>Amapá</option>
                                            <option value="AM" {{ (old('state') == 'AM') ? 'selected' : '' }}>Amazonas</option>
                                            <option value="BA" {{ (old('state') == 'BA') ? 'selected' : '' }}>Bahia</option>
                                            <option value="CE" {{ (old('state') == 'CE') ? 'selected' : '' }}>Ceará</option>
                                            <option value="DF" {{ (old('state') == 'DF') ? 'selected' : '' }}>Distrito Federal</option>
                                            <option value="ES" {{ (old('state') == 'ES') ? 'selected' : '' }}>Espírito Santo</option>
                                            <option value="GO" {{ (old('state') == 'GO') ? 'selected' : '' }}>Goiás</option>
                                            <option value="MA" {{ (old('state') == 'MA') ? 'selected' : '' }}>Maranhão</option>
                                            <option value="MT" {{ (old('state') == 'MT') ? 'selected' : '' }}>Mato Grosso</option>
                                            <option value="MS" {{ (old('state') == 'MS') ? 'selected' : '' }}>Mato Grosso do Sul</option>
                                            <option value="MG" {{ (old('state') == 'MG') ? 'selected' : '' }}>Minas Gerais</option>
                                            <option value="PA" {{ (old('state') == 'PA') ? 'selected' : '' }}>Pará</option>
                                            <option value="PB" {{ (old('state') == 'PB') ? 'selected' : '' }}>Paraíba</option>
                                            <option value="PR" {{ (old('state') == 'PR') ? 'selected' : '' }}>Paraná</option>
                                            <option value="PE" {{ (old('state') == 'PE') ? 'selected' : '' }}>Pernambuco</option>
                                            <option value="PI" {{ (old('state') == 'PI') ? 'selected' : '' }}>Piauí</option>
                                            <option value="RJ" {{ (old('state') == 'RJ') ? 'selected' : '' }}>Rio de Janeiro</option>
                                            <option value="RN" {{ (old('state') == 'RN') ? 'selected' : '' }}>Rio Grande do Norte</option>
                                            <option value="RS" {{ (old('state') == 'RS') ? 'selected' : '' }}>Rio Grande do Sul</option>
                                            <option value="RO" {{ (old('state') == 'RO') ? 'selected' : '' }}>Rondônia</option>
                                            <option value="RR" {{ (old('state') == 'RR') ? 'selected' : '' }}>Roraima</option>
                                            <option value="SC" {{ (old('state') == 'SC') ? 'selected' : '' }}>Santa Catarina</option>
                                            <option value="SP" {{ (old('state') == 'SP') ? 'selected' : '' }}>São Paulo</option>
                                            <option value="SE" {{ (old('state') == 'SE') ? 'selected' : '' }}>Sergipe</option>
                                            <option value="TO" {{ (old('state') == 'TO') ? 'selected' : '' }}>Tocantins</option>
                                        </select> 
                                        @error('state')
                                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                        @enderror
                                    </div> 
                                </div>
                            </div>                                
                        </div>
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
                        <div class="col-lg-9">
                            <div class="form-group">
                                <label for="description" class="form-label">{{ __('Descrição') }}</label>
                                <textarea id="description" cols="30" rows="10" class="form-control @error('description') is-invalid @enderror" name="description" autocomplete="description">{{ (isset($position->description)) ? $position->description : old('description') }}</textarea>
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

                    <hr>

                    <div class="form-row mb-3">
                        <div class="col-lg-9">
                            <div class="form-group">
                                <label for="observation" class="form-label">{{ __('Observação') }}</label>
                                <textarea id="observation" cols="30" rows="10" class="form-control @error('observation') is-invalid @enderror" name="observation" autocomplete="observation">{{ (isset($position->observation)) ? $position->observation : old('observation') }}</textarea>
                                @error('observation')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-row mb-3">
                        <div class="col-lg-9">
                            <div class="form-group">
                                <label for="status" class="form-label">{{ __('Status: ') }}</label>
                                {!! $position->getStatusName() !!}
                            </div>
                        </div>
                    </div>
                    <input id="status" type="hidden" name="status" value="{{ isset( $position->status ) && ( $position->status ) ? $position->status : old('status') }}">

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