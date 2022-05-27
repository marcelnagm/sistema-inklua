@extends('layouts.cms')

@section('template_title')
    Update Candidate
@endsection

@section('content')
    <div class="col-xl-12">
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h5 class="m-0 font-weight-bold text-primary">Edição de Candidato
            </h5>
        </div>                

                <div class="card-body p-4">
                        <form method="POST" action="{{ route('candidate.update', $candidate->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('cms.mapeamento-tech.candidate.form',array('role'=> $role))

                        </form>
                    
                     <td>
                             <h5 class="m-0 font-weight-bold text-primary">Outras Ações
            </h5>
                                        <form action="{{ route('candidate.destroy',$candidate->id) }}" method="DELETE">                                            
                                            <a class="btn btn-sm btn-success" href="{{ route('candidate.index') }}"><i class="fa fa-fw fa-edit"></i> Voltar a Lista</a>
                                            <a class="btn btn-sm btn-primary " href="@if ($candidate->published_at == null)  {{  route('tech.publish',$candidate->id) }} @else {{  route('tech.unpublish',$candidate->id) }} @endif"><i class="fa fa-fw fa-eye"></i> 
                                                @if($candidate->published_at == null)                                            
                                                Publicar
                                                @else
                                                Arquivar
                                                @endif
                                            </a>
                                            
                                            <a class="btn btn-sm btn-danger" href="{{ route('tech.destroy-me',$candidate->gid) }}"><i class="fa fa-fw fa-edit"></i> Remover</a>

                                    </td>
                    </div>
                </div>
            </div>
@endsection
