@extends('layouts.cms')

@section('template_title')
    Update Candidate Education Hunting
@endsection

@section('content')
   <div class="col-xl-12">
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h5 class="m-0 font-weight-bold text-primary">Edição de Formação Profissional
            </h5>
        </div>                

                    <div class="card-body">
                        <form method="POST" action="{{ route('education.update', $candidateEducationHunting->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('cms.hunting-admin..candidate-education.form')

                        </form>
                    </div>
                </div>
            </div>
@endsection
