@extends('layouts.cms')

@section('template_title')
    {{ $inkluaOffice->name ?? 'Show Inklua Office' }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Show Inklua Office</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('inklua_office.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $inkluaOffice->name }}
                        </div>
                        <div class="form-group">
                            <strong>Leader Id:</strong>
                            {{ $inkluaOffice->leader_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
