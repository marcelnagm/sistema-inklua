@extends('layouts.cms')

@section('template_title')
    {{ $candidateRole->name ?? 'Show Candidate Role' }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Show Candidate Role</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('role.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Role:</strong>
                            {{ $candidateRole->role }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
