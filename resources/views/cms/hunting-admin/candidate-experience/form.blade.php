<div class="box box-info padding-1">
    <div class="box-body">
        
            {{ Form::hidden('candidate_id', $candidateExperienceHunting->candidate_id, ['class' => 'form-control' . ($errors->has('candidate_id') ? ' is-invalid' : ''), 'placeholder' => 'Candidate Id']) }}
        </div>
        <div class="form-group">
            {{ Form::label('role') }}
            {{ Form::text('role', $candidateExperienceHunting->role, ['class' => 'form-control' . ($errors->has('role') ? ' is-invalid' : ''), 'placeholder' => 'Role']) }}
            {!! $errors->first('role', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('company') }}
            {{ Form::text('company', $candidateExperienceHunting->company, ['class' => 'form-control' . ($errors->has('company') ? ' is-invalid' : ''), 'placeholder' => 'Company']) }}
            {!! $errors->first('company', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('description') }}
            {{ Form::textarea('description', $candidateExperienceHunting->description, ['class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''), 'placeholder' => 'Description','id' => 'desc']) }}
            {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('start_at') }}
            {{ Form::text('start_at', $candidateExperienceHunting->start_at, ['class' => 'form-control datepicker' . ($errors->has('start_at') ? ' is-invalid' : ''), 'placeholder' => 'Start At']) }}
            {!! $errors->first('start_at', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('end_at') }}
            {{ Form::text('end_at', $candidateExperienceHunting->end_at, ['class' => 'form-control datepicker' . ($errors->has('end_at') ? ' is-invalid' : ''), 'placeholder' => 'End At']) }}
            {!! $errors->first('end_at', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>