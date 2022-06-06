@extends('layouts.cms')

@section('template_title')
Condition
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">

            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h5 class="m-0 font-weight-bold text-primary">Lista de Condições
                    </h5>
                </div> 

                <div class="float-right p-4">
                    <a href="{{ route('conditions_name.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                        Nova Condição
                    </a>
                </div>


                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead">
                                <tr>
                                    <th>No</th>

                                    <th>Nome</th>
                                    <th>Necessita de Intervalo</th>
                                    <th>É Financeiro</th>

                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($conditions as $condition)
                                <tr>
                                    <td>{{ ++$i }}</td>

                                    <td>{{ $condition->name }}</td>
                                    <td>{{ $condition->intervals ? 'Sim' : 'Não' }}</td>
                                    <td>{{ $condition->money ? 'Sim' : 'Não' }}</td>

                                    <td>
                                        <form action="{{ route('conditions_name.destroy',$condition->id) }}" method="POST" class="float-right">                                         
                                            <a class="btn btn-sm btn-success" href="{{ route('conditions_name.edit',$condition->id) }}"><i class="fa fa-fw fa-edit"></i> Editar</a>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> Remover</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {!! $conditions->links() !!}
        </div>
    </div>
</div>
@endsection
