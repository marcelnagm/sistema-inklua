@extends('layouts.cms')


@section('template_title')
Client
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">

            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h5 class="m-0 font-weight-bold text-primary">Clientes
                    </h5>
                </div> 
                <div class="float-right mr-4">
                    <a href="{{ route('clients.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                        Novo Cliente
                    </a>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead">
                                <tr>
                                    <th>No</th>
                                    <th>Nome Fantásia</th>
                                    <th>Razão Social</th>
                                    <th>CNPJ</th>
                                    <th>Cadastro</th>
                                    <th>Local</th>
                                    <th>Ativo</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($clients as $client)
                                <tr>
                                    <td>{{ $client->id }}</td>
                                    <td>{{ $client->fantasy_name }}</td>
                                    <td>{{ $client->formal_name }}</td>
                                    <td>{{ $client->cnpj }}</td>
                                    <td>{{ $client->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $client->local_label }}</td>
                                    <td>{{ $client->active ? 'Ativo' : 'Desativado'  }}</td>                            
                                    <td>

                                        <a class="btn btn-sm btn-primary " href="{{ route('clients.show',$client->id) }}"><i class="fa fa-fw fa-eye"></i> Exibir</a>
                                        <a class="btn btn-sm btn-success" href="{{ route('clients.edit',$client->id) }}"><i class="fa fa-fw fa-edit"></i> Editar</a>

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {!! $clients->links() !!}
        </div>
    </div>
</div>
@endsection
