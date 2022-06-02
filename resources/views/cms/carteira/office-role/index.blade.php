@extends('layouts.cms')

@section('template_title')
    Office Role
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Office Role') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('office_role.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
                                </a>
                                <a href="{{ route('inklua_office.index') }}" class="btn btn-primary btn-sm float-right mr-1"  data-placement="left">
                                  Escritórios
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
                                        
										<th>Role</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($officeRoles as $officeRole)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $officeRole->role }}</td>

                                            <td>
                                                <form action="{{ route('office_role.destroy',$officeRole->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('office_role.show',$officeRole->id) }}"><i class="fa fa-fw fa-eye"></i> Show</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('office_role.edit',$officeRole->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
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
                {!! $officeRoles->links() !!}
            </div>
        </div>
    </div>
@endsection
