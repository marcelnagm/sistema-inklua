@extends('layouts.cms')

@section('template_title')
    Create Condition
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Create Condition</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('conditions_name.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('cms.carteira.condition.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
