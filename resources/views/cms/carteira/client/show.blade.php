@extends('layouts.cms')


@section('template_title')
{{ $client->name ?? 'Show Client' }}
@endsection

@section('content')
<div class="col-sm-12">
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h5 class="m-0 font-weight-bold text-primary">Cliente   {{ ucfirst($client->formal_name ) }}
            </h5>

        </div> 
        <div class="card-body">
            <div class="float-right">
                <a class="btn btn-primary" href="{{ route('clients.index') }}"> Voltar</a>
            </div>
            <div class="form-group">
                <strong>CNPJ:</strong>
                {{ $client->cnpj }}
            </div>
            <div class="form-group">
                <strong>Razão Social:</strong>
                {{ $client->formal_name }}
            </div>
            <div class="form-group">
                <strong>Nome Fantásia:</strong>
                {{ $client->fantasy_name }}
            </div>
            <div class="form-group">
                <strong>Setor / Área:</strong>
                {{ $client->sector }}
            </div>
            <div class="form-group">
                <strong>Local:</strong>
                {{ $client->local_label }}
            </div>
            <div class="form-group">
                <strong>Ativo:</strong>
                {{ $client->active ? 'Ativo' : 'Desativado'  }}
            </div>
            <div class="form-group">
                <strong>Estado:</strong>
                {{ $client->state()->first() }}
            </div>
            
            @include('cms.carteira.client-condition.index',array('clientConditions' => $client->conditions()))
        </div>        
    </div>
</div>

@endsection
