@extends('layouts.cms')

@section('template_title')
Create Inklua Office
@endsection

@section('content')
<div class="col-xl-12">
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h5 class="m-0 font-weight-bold text-primary">Novo Escritórios / Time
            </h5>
        </div> 
        <div class="card-body">
            <form method="POST" action="{{ route('inklua_office.store') }}"  role="form" enctype="multipart/form-data">
                @csrf

                @include('cms.carteira.inklua-office.form')

            </form>
        </div>
    </div>
</div>
@endsection
