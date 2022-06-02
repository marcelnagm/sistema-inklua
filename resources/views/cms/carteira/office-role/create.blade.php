@extends('layouts.cms')

@section('template_title')
    Create Office Role
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Create Office Role</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('office_role.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('cms.carteira.office-role.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
