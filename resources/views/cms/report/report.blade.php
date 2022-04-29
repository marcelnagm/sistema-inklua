@extends('layouts.cms')

@section('content')


    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Relatórios {{ isset($donate) ? 'de inkoins doadas' : ' de inkoins geradas'}}</h1>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                @if(isset($donate))
                    <form action="{{ url("") }}/admin/report/inkoins/donation/show" method="GET" class="" id="form-search">
                @else
                    <form action="{{ url("") }}/admin/report/inkoins/show" method="GET" class="" id="form-search">
                @endif
                    @csrf
                    <div class="form-row mb-3">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="date_start" class="form-label">{{ __('Data inicial') }}</label>
                                <div class="input-group">
                                    <input id="date_start" type="text" class="form-control input-time @error('date_start') is-invalid @enderror datepicker" name="date_start" value="{{ isset( $date_start ) && ( $date_start ) ? $date_start->format('d/m/Y') : old('date_start') }}" data-js="date">
                                    @error('date_start')
                                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="date_end" class="form-label">{{ __('Data final') }}</label>
                                <div class="input-group">
                                    <input id="date_end" type="text" class="form-control input-time @error('date_end') is-invalid @enderror datepicker" name="date_end" value="{{ isset( $date_end ) && ( $date_end ) ? $date_end->format('d/m/Y') : old('date_end') }}" data-js="date">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">{{__('Buscar')}}</button>
                                    </div>
                                    @error('date_end')
                                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    @if(isset($actions) && $actions->isNotEmpty())
  
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Relatório {{ isset($donate) ? 'de inkoins doadas' : ' de inkoins geradas'}}</h6>
                    <a href="{{ url()->full() }}&type=csv" target="_blank" class="btn btn-primary">Baixar CSV</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive report-table">
                    <table class="table" id="" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 120px;">Data</th>
                                @if(isset($donate))
                                    <th scope="col">Nome</th>
                                @else
                                    <th scope="col">Título</th>
                                    <th scope="col" style="width: 120px;">Ação</th>
                                    <th scope="col" style="width: 120px;">Mídia</th>
                                @endif
                                <th scope="col" style="width: 120px;" class="text-right">Inkoins</th>
                                {{-- <th scope="col" style=" width: 220px;
                                text-align: right;"></th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($actions as $action)
                                    <tr>
                                        <td >{{ $action->created_at->format('d/m/Y')}}</td>
                                        @if(isset($donate))
                                            <td>{{$action->wallet->user->name}}</td>
                                        @else
                                            <td >{{ $action->content ? $action->content->title : 'Vaga removida' }}</td>
                                            <td >{{ $action->action }}</td>
                                            <td >{{ $action->media }}</td>
                                        @endif
                                        <td class="text-right">{{ $action->coins }}</td>
                                    </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="tfoot">
                            <tr>                            
                              <th  colspan="{{(isset($donate)) ? 2 : 4}}" class="text-right">Total: </th>
                              <td class="text-right">{{ $actions->sum('coins') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="card-footer py-3">
                <div class="d-sm-flex align-items-end justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"></h6>
                </div>
            </div>
        </div>
    @else
        @if(isset($actions))
            <h2>Nenhuma informação no período</h2>
        @endif
    @endif
@endsection