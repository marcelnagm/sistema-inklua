@extends('layouts.cms')

@section('template_title')
{{ $candidateHunting->name ?? 'Show Candidate Hunting' }}
@endsection

@section('content')
<section class="content container-fluid">
    <div class="col-xl-12">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Exibição de Candidato - Hunting
                </h6>
            </div>                
            <div class="card-body">
                <div class="float-right">
                    <a class="btn btn-primary" href="{{ route('hunt.index') }}"> Retornar a Lista</a>
                    <a class="btn btn-success" href="{{ route('candidate-hunt.edit',$candidateHunting->id) }}"><i class="fa fa-fw fa-edit"></i> Editar</a>
                </div>


                <div class="card-body">
                    
                    <div class="form-group">
                        <strong>Nome:</strong>
                        {{ $candidateHunting->name }}
                    </div>
                    <div class="form-group">
                        <strong>Sobrenome:</strong>
                        {{ $candidateHunting->surname }}
                    </div>
                    <div class="form-group">
                        <strong>Data de Nascimento:</strong>
                        {{ $candidateHunting->birth_date->format('d/m/Y')  }}
                    </div>
                    <div class="form-group">
                        <strong>Celular:</strong>
                        {{ $candidateHunting->cellphone  }}
                    </div>
                    <div class="form-group">
                        <strong>Email:</strong>
                        {{ $candidateHunting->email }}
                    </div>
                    <div class="form-group">
                        <strong>Pretensão Salarial:</strong>
                       R$ {{ $candidateHunting->payment_formatted() }}
                    </div>
                    <div class="form-group">
                        <strong>Curriculo:</strong>
                      
                      <a href='{{ route('hunt.cv',$candidateHunting->id) }}'>Baixe aqui o curriculo</a>
                      
                    </div>
                    <div class="form-group">
                        <strong>Portifolio:</strong>
                        <a href='{{ $candidateHunting->portifolio_url }}'>Curriculo </a>
                    </div>
                    <div class="form-group">
                        <strong>Linkedin:</strong>
                        <a href='{{ $candidateHunting->linkedin_url }}'>Linkedin </a>                        
                    </div>
                    <div class="form-group">
                        <strong>Pcd:</strong>
                        @include('layouts.partials.yesno',array('param' => $candidateHunting->pcd))                                                
                    </div>
                    <div class="form-group">
                        <strong>Tipo de Deficência:</strong>
                        {{ $candidateHunting->pcd_typo() }}
                    </div>
                    <div class="form-group">
                        <strong>Detalhes:</strong>
                        {{ $candidateHunting->pcd_details }}
                    </div>
                    <div class="form-group">
                        <strong>Laudo Médico:</strong>
                        <a href='{{ route('hunt.pcd_report',$candidateHunting->id) }}'>Laudo Medico </a>
                    </div>
                    <div class="form-group">
                        <strong>Estado de Residência:</strong>
                        {{ $candidateHunting->state() }}
                    </div>
                    <div class="form-group">
                        <strong>Cidade de Residência:</strong>
                        {{ $candidateHunting->city() }}
                    </div>
                    <div class="form-group">
                        <strong>Primeiro Emprego?:</strong>
                        @include('layouts.partials.yesno',array('param' => $candidateHunting->first_job))                        
                        
                    </div>
                    <div class="form-group">
                        <strong>Aceita trabalhar remoto?:</strong>
                         @include('layouts.partials.yesno',array('param' => $candidateHunting->remote))                        
                        
                    </div>
                    <div class="form-group">
                        <strong>Têm disponibilidade de mudança?:</strong>
                        @include('layouts.partials.yesno',array('param' => $candidateHunting->move_out))                                                
                    </div>
                    <div class="form-group">
                        <strong>Nivel de Inglês:</strong>
                        {{ $candidateHunting->english_level_obj() }}
                    </div>
                    <div class="form-group">
                        <strong>Gênero:</strong>
                        {{ $candidateHunting->gender() }}
                    </div>
                    <div class="form-group">
                        <strong>Raça:</strong>
                        {{ $candidateHunting->race() }}
                    </div>

                 @include('cms.hunting-admin.candidate-education.index',array('candidateEducationHuntings' => $candidateHunting->education()))
                 @include('cms.hunting-admin.candidate-experience.index',array('candidateExperienceHuntings' => $candidateHunting->experience()))
                    
                </div>
            </div>
        </div>
    </div>
    
</section>
@endsection
