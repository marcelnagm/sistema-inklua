@extends('layouts.cms')

@section('template_title')
{{ $candidateRole->name ?? 'Show Candidate Role' }}
@endsection

@section('content')
<div class="col-xl-12">
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h5 class="m-0 font-weight-bold text-primary">Exibindo Função - Mapeamento Tech
            </h5>
        </div>                
        <div class="card-body">

            <div class="form-group">
                <strong>Role:</strong>
                {{ $candidateRole->role }}
            </div>

        </div>
    </div>
</div>
@endsection
