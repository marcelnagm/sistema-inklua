@extends('layouts.cms')

@section('template_title')
    {{ $officeRole->name ?? 'Show Office Role' }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Show Office Role</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('office_role.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Role:</strong>
                            {{ $officeRole->role }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
