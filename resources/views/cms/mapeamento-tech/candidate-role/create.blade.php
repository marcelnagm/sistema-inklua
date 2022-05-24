@extends('layouts.cms')

@section('template_title')
Nova Função
@endsection

@section('content')

@includeif('partials.errors')

<div class="col-xl-12">
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h5 class="m-0 font-weight-bold text-primary">Nova Função - Mapeamento Tech
            </h5>
        </div>                
        <div class="card-body">
            <form method="POST" action="{{ route('role.store') }}"  role="form" enctype="multipart/form-data">
                @csrf

                @include('cms.mapeamento-tech.candidate-role.form')

            </form>
        </div>
    </div>
</div>
</div>
@endsection
