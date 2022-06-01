@extends('layouts.cms')

@section('template_title')
Update Candidate Hunting
@endsection

@section('content')

<!-- Area Chart -->
<div class="col-xl-12">
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Edição de Candidato - Hunting
            </h6>
        </div>                
        <div class="card-body">
            <form method="POST" action="{{ route('candidate-hunt.update', $candidateHunting->id) }}"  role="form" enctype="multipart/form-data">
                {{ method_field('PATCH') }}
                @csrf

                @include('cms.hunting-admin.candidate.form')

            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-1.9.1.js"></script>
<script>
    $(document).ready(function () {
        $('#city_id').append('<option value="{{$candidateHunting->city()->id}}" selected> {{$candidateHunting->city()->name}} </option>');
    });

</script>
@endsection
