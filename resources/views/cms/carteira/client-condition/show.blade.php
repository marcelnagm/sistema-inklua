@extends('layouts.cms')

@section('template_title')
    {{ $clientCondition->name ?? 'Show Client Condition' }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Show Client Condition</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('client_condition.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Condition Id:</strong>
                            {{ $clientCondition->condition_id }}
                        </div>
                        <div class="form-group">
                            <strong>Client Id:</strong>
                            {{ $clientCondition->client_id }}
                        </div>
                        <div class="form-group">
                            <strong>Brute:</strong>
                            {{ $clientCondition->brute }}
                        </div>
                        <div class="form-group">
                            <strong>Tax:</strong>
                            {{ $clientCondition->tax }}
                        </div>
                        <div class="form-group">
                            <strong>Guarantee:</strong>
                            {{ $clientCondition->guarantee }}
                        </div>
                        <div class="form-group">
                            <strong>Start Cond:</strong>
                            {{ $clientCondition->start_cond }}
                        </div>
                        <div class="form-group">
                            <strong>End Cond:</strong>
                            {{ $clientCondition->end_cond }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
