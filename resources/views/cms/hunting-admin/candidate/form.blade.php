<div class="box box-info padding-1">
    <div class="box-body">
        <div class="form-row mb-3">
            {{ Form::hidden('gid', $candidate->gid, ['class' => 'form-control' . ($errors->has('gid') ? ' is-invalid' : ''), 'placeholder' => 'Gid']) }}
            <div class="form-group form-control-lg">
                {{ Form::label('Função') }}
                @include('layouts.partials.select',array('list' => $role,'param' => $candidate->role_id,'name' => 'role_id'))
                
                <a href="{{ route('role.create') }}">Não tem a função? Adicione aqui </a>   
                
           </div>
        </div>
        <br> 
        <br> 
        <div class="form-row mb-3">
            <div class="form-group form-control-lg">
                {{ Form::label('Genêro') }}
                @include('layouts.partials.select',array('list' => $gender,'param' => $candidate->gender_id,'name' => 'gender_id'))            
            </div>
        </div>
        <div class="form-row mb-3">
            <div class="form-group form-control-lg">
                {{ Form::label('Raça') }}
                @include('layouts.partials.select',array('list' => $race,'param' => $candidate->race_id,'name' => 'race_id'))            
            </div>
        </div>
        <div class="form-row mb-3">
            <div class="form-group form-control-lg">
                {{ Form::label('Titulo') }}
                {{ Form::text('title', $candidate->title, ['class' => 'form-control' . ($errors->has('title') ? ' is-invalid' : ''), 'placeholder' => 'Title']) }}
                {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="form-row mb-3">
            <div class="form-group form-control-lg">
                {{ Form::label('Pretensão Salárial') }}
                {{ Form::text('payment', $candidate->payment, ['class' => 'form-control payment' . ($errors->has('payment') ? ' is-invalid' : ''), 'placeholder' => 'Payment', 'onkeypress' => 'mask_money(this)']) }}
                {!! $errors->first('payment', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="form-row mb-3">
            <div class="form-group form-control-lg">
                {{ Form::label('Estado de Residencia') }}
                @include('layouts.partials.select',array('list' => $states,'param' => $candidate->state_id,'name' => 'state_id'))
            </div>
        </div>
        <div class="form-row mb-3">
            <div class="form-group form-control-lg">
                {{ Form::label('Cidade') }}
                {{ Form::text('city', $candidate->city, ['class' => 'form-control' . ($errors->has('city') ? ' is-invalid' : ''), 'placeholder' => 'City']) }}
                {!! $errors->first('city', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="form-row mb-3">
            <div class="form-group form-control-lg">
                {{ Form::label('Deseja trabalhar remoto?') }}
                <input type="hidden" name="remote" value="0">
                <input name="remote" type="checkbox" class="" @if($candidate->remote==1) checked @endif value="1">                        
                {!! $errors->first('remote', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="form-row mb-3">
            <div class="form-group form-control-lg">
                {{ Form::label('Tem Disponibilidade De Mudança?') }}
                <input type="hidden" name="move_out" value="0"> 
                <input name="move_out" type="checkbox" class="" @if($candidate->move_out==1) checked @endif value="1"> 

                {!! $errors->first('move_out', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="form-row mb-3">
                <div class="form-group form-control-lg">
                    {{ Form::label('Descrição do candidato') }}
                    {{ Form::textarea('description', $candidate->description, ['class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''), 'placeholder' => 'Description']) }}
                    {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="form-row mb-3">
                <div class="form-group form-control-lg">
                    {{ Form::label('Nível técnico') }}
                    {{ Form::text('tecnical_degree', $candidate->tecnical_degree, ['class' => 'form-control' . ($errors->has('tecnical_degree') ? ' is-invalid' : ''), 'placeholder' => 'Tecnical Degree']) }}
                    {!! $errors->first('tecnical_degree', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="form-row mb-3">
                <div class="form-group form-control-lg">
                    {{ Form::label('Nível superior') }}
                    {{ Form::text('superior_degree', $candidate->superior_degree, ['class' => 'form-control' . ($errors->has('superior_degree') ? ' is-invalid' : ''), 'placeholder' => 'Superior Degree']) }}
                    {!! $errors->first('superior_degree', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="form-row mb-3">
                <div class="form-group form-control-lg">
                    {{ Form::label('Pós-graduação') }}
                    {{ Form::text('spec_degree', $candidate->spec_degree, ['class' => 'form-control' . ($errors->has('spec_degree') ? ' is-invalid' : ''), 'placeholder' => 'Spec Degree']) }}
                    {!! $errors->first('spec_degree', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="form-row mb-3"> 
                <div class="form-group form-control-lg">
                    {{ Form::label('MBA') }}
                    {{ Form::text('mba_degree', $candidate->mba_degree, ['class' => 'form-control' . ($errors->has('mba_degree') ? ' is-invalid' : ''), 'placeholder' => 'Mba Degree']) }}
                    {!! $errors->first('mba_degree', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="form-row mb-3">
                <div class="form-group form-control-lg">
                    {{ Form::label('Mestrado') }}
                    {{ Form::text('master_degree', $candidate->master_degree, ['class' => 'form-control' . ($errors->has('master_degree') ? ' is-invalid' : ''), 'placeholder' => 'Master Degree']) }}
                    {!! $errors->first('master_degree', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="form-row mb-3">
                <div class="form-group form-control-lg">
                    {{ Form::label('Doutorado') }}
                    {{ Form::text('doctor_degree', $candidate->doctor_degree, ['class' => 'form-control' . ($errors->has('doctor_degree') ? ' is-invalid' : ''), 'placeholder' => 'Doctor Degree']) }}
                    {!! $errors->first('doctor_degree', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="form-row mb-3">
                <div class="form-group form-control-lg">
                    {{ Form::label('Nível de Inglês') }}
                    @include('layouts.partials.select',array('list' => $english_levels,'param' => $candidate->english_level,'name' => 'english_level'))

                    {!! $errors->first('english_level', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="form-row mb-3">
                <div class="form-group form-control-lg">
                    {{ Form::label('Nome Completo') }}
                    {{ Form::text('full_name', $candidate->full_name, ['class' => 'form-control' . ($errors->has('full_name') ? ' is-invalid' : ''), 'placeholder' => 'Full Name']) }}
                    {!! $errors->first('full_name', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="form-row mb-3">
                <div class="form-group form-control-lg">
                    {{ Form::label('Celular') }}
                    {{ Form::text('cellphone', $candidate->cellphone, ['class' => 'form-control' . ($errors->has('cellphone') ? ' is-invalid' : ''), 'placeholder' => 'Cellphone','id' => 'cellphone']) }}
                    {!! $errors->first('cellphone', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="form-row mb-3">
                <div class="form-group form-control-lg">
                    {{ Form::label('Email') }}
                    {{ Form::text('email', $candidate->email, ['class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''), 'placeholder' => 'Email']) }}
                    {!! $errors->first('email', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="form-row mb-3">
                <div class="form-group form-control-lg">
                    {{ Form::label('Endereço do CV') }}
                    {{ Form::text('cv_url', $candidate->cv_url, ['class' => 'form-control' . ($errors->has('cv_url') ? ' is-invalid' : ''), 'placeholder' => 'Cv Url']) }}
                    {!! $errors->first('cv_url', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <!--<div class="form-group form-control-lg">-->
            <!--{{ Form::label('published_at') }}-->

            <!--{!! $errors->first('published_at', '<div class="invalid-feedback">:message</div>') !!}-->
            <!--</div>-->
            <div class="form-group form-control-lg">
                {{ Form::hidden('published_at', $candidate->published_at, ['class' => 'form-control' . ($errors->has('published_at') ? ' is-invalid' : ''), 'placeholder' => 'Published At']) }}

            </div>

        </div>
        <div class="box-footer mt20">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.8/jquery.mask.min.js" integrity="sha512-hAJgR+pK6+s492clbGlnrRnt2J1CJK6kZ82FZy08tm6XG2Xl/ex9oVZLE6Krz+W+Iv4Gsr8U2mGMdh0ckRH61Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    <script type="text/javascript">

function mask_money(field) {

    $(field).maskMoney({
        prefix: 'R$ ',
        allowNegative: true,
        thousands: '.',
        decimal: ','
    });
}


$('#cellphone').mask('(00) 00000-0000');
$('#cellphone').blur(function (event) {
    if ($(this).val().length == 15) { // Celular com 9 dígitos + 2 dígitos DDD e 4 da máscara
        $(this).mask('(00) 00000-0000');
    } else {
        $(this).mask('(00) 0000-00000');
    }
});

    </script>