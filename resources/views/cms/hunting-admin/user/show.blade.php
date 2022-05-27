@extends('layouts.cms')

@section('template_title')
    {{ $user->name ?? 'Show User' }}
@endsection

@section('content')
   <div class="col-xl-12">
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h5 class="m-0 font-weight-bold text-primary">Detalhes do Recrutador - Hunting
            </h5>
        </div>                

                

                    <div class="card-body">
                        
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>
                        </div>
                
                        <div class="form-group">
                            <strong>Nome:</strong>
                            {{ $user->name }}
                        </div>
                        <div class="form-group">
                            <strong>Sobrenome:</strong>
                            {{ $user->lastname }}
                        </div>
                        <div class="form-group">
                            <strong>Email:</strong>
                            {{ $user->email }}
                        </div>
                        <div class="form-group">
                            <strong>Cnpj:</strong>
                            {{ $user->cnpj }}
                        </div>
                        <div class="form-group">
                            <strong>Nome Fantasia:</strong>
                            {{ $user->fantasy_name }}
                        </div>
                        <div class="form-group">
                            <strong>Telefone:</strong>
                            {{ $user->phone }}
                        </div>
                        <div class="form-group">
                            <strong>É Inkluer?:</strong>
                                @if($user->isInklua())
                                @include('layouts.partials.yesno',array('param' => 1))  
                                
                                
                                @else
                                @include('layouts.partials.yesno',array('param' => 0))
                                @endif
                                
                                
                                
                        </div>
                       
                        <div class="p-md-4">
                            <h6>Ações</h6>
                            @if($user->isInklua())
                            <a class="btn btn-sm btn-danger" href="{{ route('users.revoke',$user->id) }}"><i class="fa fa-fw fa-edit"></i> Revogar</a>                            
                            @else
                            <a class="btn btn-sm btn-primary" href="{{ route('users.promote',$user->id) }}"><i class="fa fa-fw fa-edit"></i> Promover</<a>
                            @endif
                            
                            <a class="btn btn-sm btn-success" href="{{ route('users.edit',$user->id) }}"><i class="fa fa-fw fa-edit"></i> Editar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
