<div class="box box-info padding-1">
    <div class="box-body">

        {{ Form::hidden('candidate_id', $candidateExperienceHunting->candidate_id, ['class' => 'form-control' . ($errors->has('candidate_id') ? ' is-invalid' : ''), 'placeholder' => 'Candidate Id']) }}
    </div>
    <div class="form-group col-lg-6">
        {{ Form::label('Função') }}
        {{ Form::text('role', $candidateExperienceHunting->role, ['class' => 'form-control' . ($errors->has('role') ? ' is-invalid' : ''), 'placeholder' => 'Função']) }}
        {!! $errors->first('role', '<div class="invalid-feedback">:message</div>') !!}
    </div>
    <div class="form-group col-lg-6">
        {{ Form::label('Empresa') }}
        {{ Form::text('company', $candidateExperienceHunting->company, ['class' => 'form-control' . ($errors->has('company') ? ' is-invalid' : ''), 'placeholder' => 'Empresa']) }}
        {!! $errors->first('company', '<div class="invalid-feedback">:message</div>') !!}
    </div>
    <div class="form-group">
        {{ Form::label('Descrição') }}
        {{ Form::textarea('description', $candidateExperienceHunting->description, ['class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''), 'placeholder' => 'Descrição','id' => 'desc']) }}
        {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
    </div>
    <div class="form-group">
        {{ Form::label('Inicio em') }}
        {{ Form::text('start_at', $candidateExperienceHunting->start_at ? $candidateExperienceHunting->start_at->format('d/m/Y') : "", ['class' => 'form-control datepicker' . ($errors->has('start_at') ? ' is-invalid' : ''), 'placeholder' => 'Inicio em']) }}
        {!! $errors->first('start_at', '<div class="invalid-feedback">:message</div>') !!}
    </div>
    <div class="form-group">
        {{ Form::label('Fim em') }}
        {{ Form::text('end_at', $candidateExperienceHunting->end_at ? $candidateExperienceHunting->end_at->format('d/m/Y') : '' , ['class' => 'form-control datepicker' . ($errors->has('end_at') ? ' is-invalid' : ''), 'placeholder' => 'Fim em']) }}
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