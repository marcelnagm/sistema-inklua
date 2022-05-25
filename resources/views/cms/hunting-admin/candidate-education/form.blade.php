<div class="box box-info padding-1">
    <div class="box-body">

        {{ Form::hidden('candidate_id', $candidateEducationHunting->candidate_id, ['class' => 'form-control' . ($errors->has('candidate_id') ? ' is-invalid' : ''), 'placeholder' => 'Candidate Id']) }}

    </div>
    <div class="form-row mb-3">
        <div class="col-lg-9">
            <div class="form-group">
                {{ Form::label('Nível Educacional') }}
                @include('layouts.partials.select',array('list' => $level,'param' => $candidateEducationHunting->level_education_id,'name' => 'level_education_id'))
                {!! $errors->first('pcd_type_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('institute') }}
        {{ Form::text('institute', $candidateEducationHunting->institute, ['class' => 'form-control' . ($errors->has('institute') ? ' is-invalid' : ''), 'placeholder' => 'Institute']) }}
        {!! $errors->first('institute', '<div class="invalid-feedback">:message</div>') !!}
    </div>
    <div class="form-group">
        {{ Form::label('course') }}
        {{ Form::text('course', $candidateEducationHunting->course, ['class' => 'form-control' . ($errors->has('course') ? ' is-invalid' : ''), 'placeholder' => 'Course']) }}
        {!! $errors->first('course', '<div class="invalid-feedback">:message</div>') !!}
    </div>
    <div class="form-group">
        {{ Form::label('start_at') }}
        {{ Form::text('start_at', $candidateEducationHunting->start_at, ['class' => 'form-control datepicker' . ($errors->has('start_at') ? ' is-invalid' : ''), 'placeholder' => 'Start At']) }}
        {!! $errors->first('start_at', '<div class="invalid-feedback">:message</div>') !!}
    </div>
    <div class="form-group">
        {{ Form::label('end_at') }}
        {{ Form::text('end_at', $candidateEducationHunting->end_at, ['class' => 'form-control datepicker' . ($errors->has('end_at') ? ' is-invalid' : ''), 'placeholder' => 'End At']) }}
        {!! $errors->first('end_at', '<div class="invalid-feedback">:message</div>') !!}
    </div>

</div>
<div class="box-footer mt20">
    <button type="submit" class="btn btn-primary">Submit</button>
</div>
</div>