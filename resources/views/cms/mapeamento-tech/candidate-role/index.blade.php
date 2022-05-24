@extends('layouts.cms')

@section('template_title')
Função
@endsection

@section('content')
<div class="col-xl-12">
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h5 class="m-0 font-weight-bold text-primary">Lista de Funções - Mapeamento Tech
            </h5>
        </div>                
        <div class="card-body">

            <div class="float-right">
                <a href="{{ route('role.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                    {{ __('Create New') }}
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead">
                        <tr>
                            <th>No</th>

                            <th>Função</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($candidateRoles as $candidateRole)
                        <tr>
                            <td>{{ ++$i }}</td>

                            <td>{{ $candidateRole->role }}</td>

                            <td>
                                <form action="{{ route('role.destroy',$candidateRole->id) }}" method="POST">
                                    <a class="btn btn-sm btn-primary " href="{{ route('role.show',$candidateRole->id) }}"><i class="fa fa-fw fa-eye"></i> Show</a>
                                    <a class="btn btn-sm btn-success" href="{{ route('role.edit',$candidateRole->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {!! $candidateRoles->links() !!}
</div>
@endsection
