@extends('layouts.cms')

@section('template_title')
    Create Client Condition
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Create Client Condition</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('client_condition.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('cms.carteira.client-condition.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
