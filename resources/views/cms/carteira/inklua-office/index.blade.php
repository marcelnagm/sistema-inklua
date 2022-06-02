@extends('layouts.cms')

@section('template_title')
Inklua Office
@endsection

@section('content')


<div class="col-xl-12">
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h5 class="m-0 font-weight-bold text-primary">Lista de Escritórios / Time
            </h5>
        </div>  
        <div class="float-right p-4">
            <a href="{{ route('inklua_office.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                Novo Escritório
            </a>
            <a href="{{ route('office_role.index') }}" class="btn btn-primary btn-sm float-right mr-1"  data-placement="left">
                Lista de Funções
            </a>
        </div>
        


        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead">
                        <tr>
                            <th>No</th>

                            <th>Nome</th>
                            <th>Lider</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inkluaOffices as $inkluaOffice)
                        <tr>
                            <td>{{ ++$i }}</td>

                            <td>{{ $inkluaOffice->name }}</td>
                            <td>
                                <?php $off = $inkluaOffice->user()->first(); ?>
                                {{ $off ? $off->fullname().' - INKLUER#'.$off->id: 'Não Atribuido'}}

                            </td>

                            <td>
                                <form action="{{ route('inklua_office.destroy',$inkluaOffice->id) }}" method="POST">
                                    <a class="btn btn-sm btn-primary " href="{{ route('inklua_office.show',$inkluaOffice->id) }}"><i class="fa fa-fw fa-eye"></i> Exibir</a>
                                    <a class="btn btn-sm btn-success" href="{{ route('inklua_office.edit',$inkluaOffice->id) }}"><i class="fa fa-fw fa-edit"></i> Editar</a>
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
    {!! $inkluaOffices->links() !!}
</div>
@endsection
