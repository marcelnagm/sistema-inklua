@extends('layouts.cms')

@section('template_title')
Função
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <h1 class="h3 mb-2 text-gray-800">Funções</h1>

                        <div class="float-right">
                            <a href="{{ route('role.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                {{ __('Create New') }}
                            </a>
                        </div>
                    </div>
                </div>
                @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
                @endif

                <div class="card-body">
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
    </div>
</div>
@endsection
