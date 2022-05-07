@extends('layouts.cms')

@section('template_title')
    Update Candidate
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Update Candidate</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('candidate.update', $candidate->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('candidate.form',array('role'=> $role))

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
