@extends('layouts.cms')

@section('template_title')
    Create Candidate English Level
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Create Candidate English Level</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('english_level.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('cms.candidate-english-level.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
