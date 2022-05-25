@extends('layouts.cms')

@section('template_title')
    {{ $candidateEducationHunting->name ?? 'Show Candidate Education Hunting' }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Show Candidate Education Hunting</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('education.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Candidate Id:</strong>
                            {{ $candidateEducationHunting->candidate_id }}
                        </div>
                        <div class="form-group">
                            <strong>Level Education Id:</strong>
                            {{ $candidateEducationHunting->level_education_id }}
                        </div>
                        <div class="form-group">
                            <strong>Institute:</strong>
                            {{ $candidateEducationHunting->institute }}
                        </div>
                        <div class="form-group">
                            <strong>Course:</strong>
                            {{ $candidateEducationHunting->course }}
                        </div>
                        <div class="form-group">
                            <strong>Start At:</strong>
                            {{ $candidateEducationHunting->start_at }}
                        </div>
                        <div class="form-group">
                            <strong>End At:</strong>
                            {{ $candidateEducationHunting->end_at }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
