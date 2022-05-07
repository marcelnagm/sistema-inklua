@extends('layouts.cms')

@section('template_title')
    Update Candidate English Level
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Update Candidate English Level</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('english_level.update', $candidateEnglishLevel->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('cms.candidate-english-level.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
