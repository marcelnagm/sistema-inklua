@extends('layouts.cms')

@section('template_title')
    Candidate English Level
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h1 class="h3 mb-2 text-gray-800">Nível de Inglês</h1>

                             <div class="float-right">
                                <a href="{{ route('english_level.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
										<th>Level</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($candidateEnglishLevels as $candidateEnglishLevel)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $candidateEnglishLevel->level }}</td>

                                            <td>
                                                <form action="{{ route('english_level.destroy',$candidateEnglishLevel->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('english_level.show',$candidateEnglishLevel->id) }}"><i class="fa fa-fw fa-eye"></i> Show</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('english_level.edit',$candidateEnglishLevel->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
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
                {!! $candidateEnglishLevels->links() !!}
            </div>
        </div>
    </div>
@endsection
