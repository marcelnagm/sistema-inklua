@extends('layouts.cms')

@section('template_title')
    {{ $candidateEnglishLevel->name ?? 'Show Candidate English Level' }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Show Candidate English Level</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('english_level.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Level:</strong>
                            {{ $candidateEnglishLevel->level }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
