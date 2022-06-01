@extends('layouts.cms')

@section('template_title')
Update Candidate Experience Hunting
@endsection

@section('content')
<div class="col-xl-12">
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h5 class="m-0 font-weight-bold text-primary">Edição de Experiência Profissional
            </h5>
        </div>                


        <div class="card-body">
            <form method="POST" action="{{ route('work.update', $candidateExperienceHunting->id) }}"  role="form" enctype="multipart/form-data">
                {{ method_field('PATCH') }}
                @csrf

                @include('cms.hunting-admin.candidate-experience.form')

            </form>
        </div>
    </div>
</div>
@endsection
