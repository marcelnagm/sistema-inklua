@extends('layouts.cms')

@section('template_title')
    {{ $candidateExperienceHunting->name ?? 'Show Candidate Experience Hunting' }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Show Candidate Experience Hunting</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('work.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Candidate Id:</strong>
                            {{ $candidateExperienceHunting->candidate_id }}
                        </div>
                        <div class="form-group">
                            <strong>Role:</strong>
                            {{ $candidateExperienceHunting->role }}
                        </div>
                        <div class="form-group">
                            <strong>Company:</strong>
                            {{ $candidateExperienceHunting->company }}
                        </div>
                        <div class="form-group">
                            <strong>Description:</strong>
                            {{ $candidateExperienceHunting->description }}
                        </div>
                        <div class="form-group">
                            <strong>Start At:</strong>
                            {{ $candidateExperienceHunting->start_at }}
                        </div>
                        <div class="form-group">
                            <strong>End At:</strong>
                            {{ $candidateExperienceHunting->end_at }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
