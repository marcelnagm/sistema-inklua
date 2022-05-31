@extends('layouts.cms')

@section('template_title')
{{ $inkluaOffice->name ?? 'Show Inklua Office' }}
@endsection

@section('content')
<div class="col-xl-12">
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h5 class="m-0 font-weight-bold text-primary">Escritório
            </h5>
        </div>                      
        

        <div class="card-body">
<div class="float-right">
            <a class="btn btn-primary" href="{{ route('inklua_office.index') }}"> Voltar</a>
        </div>

            <div class="form-group">
                <strong>Nome:</strong>
                {{ $inkluaOffice->name }}
            </div>
            <div class="form-group">
                <strong>Lider:</strong>
                <?php $off = $inkluaOffice->user()->first(); ?>
                <a href="{{route('users.show',$off)}}">
                {{ $off ? $off->fullname().' - INKLUER#'.$off->id: 'Não Atribuido'}}
                </a>
            </div>
            <div class="form-group">
                <strong>Associados:</strong>
                @foreach( $inkluaOffice->inkluaUsersActive()->get() as $user)
                <br>
                <a href="{{route('users.show',$user->user()->id)}}">
                <strong>
                {{  $user->user()->fullname().' - INKLUER#'.$user->user()->id}}
                </strong>
                </a>
                @endforeach
            </div>

        </div>
    </div>
</div>

@endsection
0