@extends('layouts.cms')

@section('template_title')
    Update Client Condition
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Update Client Condition</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('client_condition.update', $clientCondition->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('cms.carteira.client-condition.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
