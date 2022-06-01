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
                @if($off != null)
                <a href="{{route('users.show',$off)}}">
                    {{ $off ? $off->fullname().' - INKLUER#'.$off->id: 'Não Atribuido'}}
                </a>
                @else
                <marquee class='alert-warning fa-2x'> NÃO EXISTE LIDER ASSOCIADO</marquee>
                @endif
                <br>
                <strong>PFL - Programa de Formação de Lider:</strong>
                <?php $off = $inkluaOffice->user_pfl()->first(); ?>
                @if($off != null)
                <a href="{{route('users.show',$off)}}">
                    {{ $off ? $off->fullname().' - INKLUER#'.$off->id: 'Não Atribuido'}}
                </a>
                @else
                <marquee class='alert-warning fa-2x'> NÃO EXISTE PFL - Programa de Formação de Lider</marquee>
                @endif
            </div>

            <div class="form-group">
                <strong>Histórico Geral:</strong>


                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead">
                            <tr>
                                <th>Nome</th>
                                <th>Cargo</th>
                                <th>Cadastro</th>
                                <th>Inicio</th>
                                <th>Fim</th>
                                <th>Ativo</th>
                                <th>Ações</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $inkluaOffice->inkluaUsers()->get() as $user)            
                            @if($user != null)
                            <tr>
                                <td>
                                    {{  $user->user()->fullname().' - INKLUER#'.$user->user()->id}}
                                </td>
                                <td>
                                    {{  $user->role()}}
                                </td>
                                <td>
                                    {{  $user->created_at->format('d/m/Y')}}
                                </td>
                                <td>
                                    {{  $user->start_at->format('d/m/Y')}}
                                </td>
                                <td>

                                    {{  $user->end_at ? $user->end_at->format('d/m/Y') : '' }}
                                </td>
                                <td>
                                    @include('layouts.partials.yesno',array('param' => $user->active))                                                
                                </td>
                                <td>
                                    <a href="{{route('users.show',$user->user()->id)}}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                        Visualizar
                                    </a>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>
</div>

@endsection
0