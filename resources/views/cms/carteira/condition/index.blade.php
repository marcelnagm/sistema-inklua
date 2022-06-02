@extends('layouts.cms')

@section('template_title')
    Condition
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Condition') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('conditions_name.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
                                </a>
                              </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        
										<th>Name</th>
										<th>Intervals</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($conditions as $condition)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $condition->name }}</td>
											<td>{{ $condition->intervals }}</td>

                                            <td>
                                                <form action="{{ route('conditions_name.destroy',$condition->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('conditions_name.show',$condition->id) }}"><i class="fa fa-fw fa-eye"></i> Show</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('conditions_name.edit',$condition->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
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
                {!! $conditions->links() !!}
            </div>
        </div>
    </div>
@endsection
