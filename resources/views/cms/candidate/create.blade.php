@extends('layouts.cms')

@section('template_title')
    Create Candidate
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
             

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Create Candidate</span>
                    </div>
                    <div class="card-body">
                        
                        <form method="POST" action="{{ route('candidate.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('cms.candidate.form',array('role'=>$role))

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
