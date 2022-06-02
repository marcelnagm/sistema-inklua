<div class="box box-info padding-1">
    <div class="box-body">
        {{ Form::hidden('client_id', $clientCondition->client_id, ['class' => 'form-control' . ($errors->has('client_id') ? ' is-invalid' : ''), 'placeholder' => 'Client Id']) }}
        <div class="form-group">
            {{ Form::label('condition_id') }}
            @include('layouts.partials.select',array('list' => $condtions,'param' => $clientCondition->condition_id,'id' => 'condition_id','name' => 'condition_id','onchange' => 'depend("state_id");'))
            {!! $errors->first('condition_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
            
            
        <div class="form-group">
            {{ Form::label('brute') }}
            {{ Form::text('brute', $clientCondition->brute, ['class' => 'form-control' . ($errors->has('brute') ? ' is-invalid' : ''), 'placeholder' => 'Brute']) }}
            {!! $errors->first('brute', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('tax') }}
            {{ Form::text('tax', $clientCondition->tax, ['class' => 'form-control' . ($errors->has('tax') ? ' is-invalid' : ''), 'placeholder' => 'Tax']) }}
            {!! $errors->first('tax', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('guarantee') }}
            {{ Form::text('guarantee', $clientCondition->guarantee, ['class' => 'form-control' . ($errors->has('guarantee') ? ' is-invalid' : ''), 'placeholder' => 'Guarantee']) }}
            {!! $errors->first('guarantee', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('start_cond') }}
            {{ Form::text('start_cond', $clientCondition->start_cond, ['class' => 'form-control' . ($errors->has('start_cond') ? ' is-invalid' : ''), 'placeholder' => 'Start Cond']) }}
            {!! $errors->first('start_cond', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('end_cond') }}
            {{ Form::text('end_cond', $clientCondition->end_cond, ['class' => 'form-control' . ($errors->has('end_cond') ? ' is-invalid' : ''), 'placeholder' => 'End Cond']) }}
            {!! $errors->first('end_cond', '<div class="invalid-feedback">:message</div>') !!}
        </div>

            <input type="hidden" name="active" value="1">
    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>