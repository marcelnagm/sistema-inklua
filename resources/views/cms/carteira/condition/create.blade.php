@extends('layouts.cms')

@section('template_title')
    Create Condition
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
        <div class="col-sm-12">

            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h5 class="m-0 font-weight-bold text-primary">Nova Condição
                    </h5>
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
