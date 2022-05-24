@extends('layouts.cms')

@section('template_title')
Update Candidate Role
@endsection

@section('content')
<div class="col-xl-12">
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h5 class="m-0 font-weight-bold text-primary">Editando Função - Mapeamento Tech
            </h5>
        </div>                
        <div class="card-body">

            <form method="POST" action="{{ route('role.update', $candidateRole->id) }}"  role="form" enctype="multipart/form-data">
                {{ method_field('PATCH') }}
                @csrf

                @include('cms.mapeamento-tech.candidate-role.form')

            </form>
        </div>
    </div>
</div>
@endsection
