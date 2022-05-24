@extends('layouts.cms')

@section('template_title')
{{ $candidate->name ?? 'Show Candidate' }}
@endsection

@section('content')
<div class="col-xl-12">
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h5 class="m-0 font-weight-bold text-primary">Exibição de Candidato
            </h5>
        </div>                


        <div class="float-right">
            <a class="btn btn-primary" href="{{ route('candidate.index') }}"> Back</a>
        </div>
    

    <div class="card-body">
        <table class="table table-striped table-hover">

            <tbody>

                <tr>
                    <td>Codigo do candidato</td>                                    
                    <td>KUNLA-{{ $candidate->id}}</td>                                    
                </tr>
                <tr>
                    <td>Função</td>                                    
                    <td>{{ $candidate->role() }}</td>                                    
                </tr>
                <tr>
                    <td>Titulo</td>                                    
                    <td>  {{ $candidate->title }}</td>                                    
                </tr>
                <tr>
                    <td>Pretensão Salarial</td>                                    
                    <td> 
                        R${{ $candidate->payment_formatted() }}
                    </td>                                    
                </tr>
                <tr>
                    <td>Estado</td>                                    
                    <td> 
                        {{ $candidate->state() }}
                    </td>                                    
                </tr>
                <tr>
                    <td>Cidade</td>                                    
                    <td> 
                        {{ $candidate->city }}
                    </td>                                    
                </tr>
                <tr>
                    <td>Remoto:</td>                                    
                    <td> 
                        @include('layouts.partials.yesno',array('param' => $candidate->remote ==1)  )
                    </td>                                    
                </tr>
                <tr>
                    <td>Disponilidade de mudança:</td>                                    
                    <td> 
                        {{ $candidate->move_out ==0 ? 'Não possui disponibilidade de mudança' : 'Possui disponibilidade de mudança'}}
                    </td>                                    
                </tr>
                <tr>
                    <td>Descrição:</td>                                    
                    <td> 
                        {{ $candidate->description }}
                    </td>                                    
                </tr>
                <tr>
                    <td>Nivel Técnico:</td>                                    
                    <td> 
                        {{ $candidate->tecnical_degree }}
                    </td>                                    
                </tr>
                <tr>
                    <td>Nivel Superior:</td>                                    
                    <td> 
                        {{ $candidate->superior_degree }}
                    </td>                                    
                </tr>
                <tr>
                    <td>Pós-graduação:</td>                                    
                    <td> 
                        {{ $candidate->spec_degree }}
                    </td>                                    
                </tr>
                <tr>
                    <td>MBA:</td>                                    
                    <td> 
                        {{ $candidate->mba_degree }}
                    </td>                                    
                </tr>
                <tr>
                    <td>Mestrado:</td>                                    
                    <td> 
                        {{ $candidate->master_degree }}
                    </td>                                    
                </tr>
                <tr>
                    <td>Doutorado:</td>                                    
                    <td> 
                        {{ $candidate->doctor_degree }}
                    </td>                                    
                </tr>
                <tr>
                    <td>Nível de inglês:</td>                                    
                    <td> 
                        {{ $candidate->english_level_obj() }}
                    </td>                                    
                </tr>
                <tr>
                    <td>Nome completo:</td>                                    
                    <td> 
                        {{ $candidate->full_name }}
                    </td>                                    
                </tr>
                <tr>
                    <td>Genêro:</td>                                    
                    <td> 
                        {{ $candidate->gender() }}
                    </td>                                    
                </tr>
                <tr>
                    <td>Raça:</td>                                    
                    <td> 
                        {{ $candidate->race() }}
                    </td>                                    
                </tr>
                <tr>
                    <td>Celular:</td>                                    
                    <td> 
                        {{ $candidate->phone() }}
                    </td>                                    
                </tr>
                <tr>
                    <td>Email:</td>                                    
                    <td> 
                        {{ $candidate->email }}
                    </td>                                    
                </tr>
                <tr>
                    <td>Cv URL:</td>                                    
                    <td> 
                        <a target="_blank" href="{{ $candidate->cv_url }}">Link </a>

                    </td>                                    
                </tr>
                <tr>
                    <td>Publicado em:</td>                                    
                    <td> 
                        {{ $candidate->published_at == NULL? 'Não Publicado' : $candidate->published_at->format('d/m/Y H:i') }}
                    </td>                                    
                </tr>
                <tr>
                    <td>Status:</td>                                    
                    <td> 
                        {{ $candidate->status() }}
                    </td>                                    
                </tr>

            </tbody>
        </table>

    </div>
    </div>
</div>
@endsection
