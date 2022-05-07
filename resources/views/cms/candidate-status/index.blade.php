@extends('layouts.cms')

@section('template_title')
    Candidate Status
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                        <h1 class="h3 mb-2 text-gray-800">Status de Candidato</h1>
                            
                             <div class="float-right">
                                <a href="{{ route('status.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
										<th>Status</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($candidateStatuses as $candidateStatus)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $candidateStatus->status }}</td>

                                            <td>
                                                <form action="{{ route('status.destroy',$candidateStatus->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('status.show',$candidateStatus->id) }}"><i class="fa fa-fw fa-eye"></i> Show</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('status.edit',$candidateStatus->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
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
                {!! $candidateStatuses->links() !!}
            </div>
        </div>
    </div>
@endsection
