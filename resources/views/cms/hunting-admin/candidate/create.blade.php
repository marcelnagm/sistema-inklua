@extends('layouts.cms')

@section('template_title')
    Create Candidate Hunting
@endsection

@section('content')
  <div class="col-xl-12">
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Adição de Candidato - Hunting
            </h6>
        </div>                

        <div class="card-body">
                        <form method="POST" action="{{ route('candidate-hunt.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('cms.hunting-admin.candidate.form')

                        </form>
                    </div>
                </div>
                </div>
            
    </section>
@endsection
