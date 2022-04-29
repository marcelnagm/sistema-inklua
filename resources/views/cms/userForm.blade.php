@extends('layouts.cms')

@section('content')


<div class="row">

    <!-- Area Chart -->
    <div class="col-xl-12">
      <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Gestão de usuário</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
            @if( empty($user->id))
                <form action="{{ url("") }}/admin/usuarios" method="post" id="form-user">
            @else
                <form action="{{ url("") }}/admin/usuarios/{{$user->id}}" method="post" class=""  id="form-user">
                @method('PUT')
            @endif

            @csrf
        
            <div class="form-row mb-3">
                <div class="col-lg-9">
                    <div class="form-group">
                        <label for="name" class="form-label">{{ __('Nome') }}</label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ (isset($user->name)) ? $user->name : old('name') }}"  autocomplete="name" >
                        @error('name')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-row mb-3">
                <div class="col-lg-9">
                    <div class="form-group">
                        <label for="email" class="form-label">{{ __('E-mail') }}</label>
                        <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ (isset($user->email)) ? $user->email : old('email') }}"  autocomplete="email" >
                        @error('email')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-row mb-3">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}"  autocomplete="password" >
                        @error('password')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">{{ __('Confirmar Senha') }}</label>
                        <input id="password_confirmation" type="password" class="form-control" name="password_confirmation"  autocomplete="password_confirmation">
                    </div>
                </div>
            </div>

            @if( empty($user->id) )
                <button type="submit" class="btn btn-primary">Adicionar</button>
            @else
                <button type="submit" class="btn btn-primary">Salvar</button>
            @endif
            </form>
        </div>
      </div>
    </div>

   
  </div>
@endsection