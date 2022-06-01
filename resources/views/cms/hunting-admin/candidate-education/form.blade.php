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
        {{ Form::label('Instituição') }}
        {{ Form::text('institute', $candidateEducationHunting->institute, ['class' => 'form-control' . ($errors->has('institute') ? ' is-invalid' : ''), 'placeholder' => 'Instituição']) }}
        {!! $errors->first('institute', '<div class="invalid-feedback">:message</div>') !!}
    </div>
    <div class="form-group">
        {{ Form::label('Curso') }}
        {{ Form::text('course', $candidateEducationHunting->course, ['class' => 'form-control' . ($errors->has('course') ? ' is-invalid' : ''), 'placeholder' => 'Curso']) }}
        {!! $errors->first('course', '<div class="invalid-feedback">:message</div>') !!}
    </div>
    <div class="form-group">
        {{ Form::label('Inicio em:') }}
        {{ Form::text('start_at', $candidateEducationHunting->start_at? $candidateEducationHunting->start_at->format('d/m/Y') : "", ['class' => 'form-control datepicker' . ($errors->has('start_at') ? ' is-invalid' : ''), 'placeholder' => 'Inicio em:']) }}
        {!! $errors->first('start_at', '<div class="invalid-feedback">:message</div>') !!}
    </div>
    <div class="form-group">
        {{ Form::label('Fim em:') }}
        {{ Form::text('end_at', $candidateEducationHunting->end_at ? $candidateEducationHunting->end_at->format('d/m/Y') : '', ['class' => 'form-control datepicker' . ($errors->has('end_at') ? ' is-invalid' : ''), 'placeholder' => '']) }}
        <p>
            Preencha se houver concluido, caso contrário deixe em branco
        </p>
        {!! $errors->first('end_at', '<div class="invalid-feedback">:message</div>') !!}
    </div>

</div>
<div class="box-footer mt20">
    <button type="submit" class="btn btn-primary">Salvar</button>
</div>
</div>