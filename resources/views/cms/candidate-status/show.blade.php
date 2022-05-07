@extends('layouts.cms')

@section('template_title')
    {{ $candidateStatus->name ?? 'Show Candidate Status' }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Show Candidate Status</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('status.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Status:</strong>
                            {{ $candidateStatus->status }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
