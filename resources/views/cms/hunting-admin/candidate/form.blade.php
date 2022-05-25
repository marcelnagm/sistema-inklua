<div class="box box-info padding-1">
    <div class="box-body">

        {{ Form::hidden('gid', $candidateHunting->gid, ['class' => 'form-control' . ($errors->has('gid') ? ' is-invalid' : ''), 'placeholder' => 'Gid']) }}        <div class="form-group">
            {{ Form::label('name') }}
            {{ Form::text('name', $candidateHunting->name, ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Name']) }}
            {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('surname') }}
            {{ Form::text('surname', $candidateHunting->surname, ['class' => 'form-control' . ($errors->has('surname') ? ' is-invalid' : ''), 'placeholder' => 'Surname']) }}
            {!! $errors->first('surname', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('birth_date') }}
            {{ Form::text('birth_date', $candidateHunting->birth_date , ['class' => 'form-control datepicker' . ($errors->has('birth_date') ? ' is-invalid' : ''), 'placeholder' => 'Birth Date']) }}
            {!! $errors->first('birth_date', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('cellphone') }}
            {{ Form::text('cellphone', $candidateHunting->cellphone, ['class' => 'form-control' . ($errors->has('cellphone') ? ' is-invalid' : ''), 'placeholder' => 'Cellphone']) }}
            {!! $errors->first('cellphone', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('email') }}
            {{ Form::text('email', $candidateHunting->email, ['class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''), 'placeholder' => 'Email']) }}
            {!! $errors->first('email', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('payment') }}
            {{ Form::text('payment', $candidateHunting->payment, ['class' => 'form-control' . ($errors->has('payment') ? ' is-invalid' : ''), 'placeholder' => 'Payment']) }}
            {!! $errors->first('payment', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Curriculo em ') }}
            {{ Form::file('cv_path',  ['class' => 'form-control' . ($errors->has('cv_path') ? ' is-invalid' : ''), 'placeholder' => 'Cv Path']) }}
            {!! $errors->first('cv_path', '<div class="invalid-feedback">:message</div>') !!}
            @isset($candidateHunting->cv_path)
            <a href='{{ route('hunt.cv',$candidateHunting->id) }}'>Baixe aqui o curriculo</a>
            @endisset
        </div>
        <div class="form-group">
            {{ Form::label('portifolio_url') }}
            {{ Form::text('portifolio_url', $candidateHunting->portifolio_url, ['class' => 'form-control' . ($errors->has('portifolio_url') ? ' is-invalid' : ''), 'placeholder' => 'Portifolio Url']) }}
            {!! $errors->first('portifolio_url', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('linkedin_url') }}
            {{ Form::text('linkedin_url', $candidateHunting->linkedin_url, ['class' => 'form-control' . ($errors->has('linkedin_url') ? ' is-invalid' : ''), 'placeholder' => 'Linkedin Url']) }}
            {!! $errors->first('linkedin_url', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-row mb-3">
            <div class="form-group ">
                {{ Form::label('Portador de  deficiência?') }}
                <input type="hidden" name="pcd" value="0">
                <input name="pcd" type="checkbox" class="" @if($candidateHunting->pcd==1) checked @endif value="1">                        
                {!! $errors->first('pcd', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="form-row mb-3">
            <div class="col-lg-9">
                <div class="form-group">
                    {{ Form::label('pcd_type_id') }}
                    @include('layouts.partials.select',array('list' => $pcd,'param' => $candidateHunting->pcd_type_id,'name' => 'pcd_type_id'))
                    {!! $errors->first('pcd_type_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('pcd_details') }}
            {{ Form::textarea('pcd_details', $candidateHunting->pcd_details, ['class' => 'form-control' . ($errors->has('pcd_details') ? ' is-invalid' : ''), 'placeholder' => 'Pcd Details']) }}
            {!! $errors->first('pcd_details', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('pcd_report') }}
            {{ Form::file('pcd_report',['class' => 'form-control' . ($errors->has('pcd_report') ? ' is-invalid' : ''), 'placeholder' => 'Pcd Report']) }}
            {!! $errors->first('pcd_report', '<div class="invalid-feedback">:message</div>') !!}
            @isset($candidateHunting->pcd_report)
            <a href='{{ route('hunt.pcd_report',$candidateHunting->id) }}'>Laudo Medico </a>
            @endisset
        </div>
        <div class="form-row mb-3">
            <div class="col-lg-9">
                <div class="form-group">
                    {{ Form::label('Estado de Residencia') }}
                    @include('layouts.partials.select',array('list' => $states,'param' => $candidateHunting->state_id,'id' => 'state_id','name' => 'state_id','onchange' => 'depend("state_id");'))
                </div>
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('city_id') }}       
            @isset($candidateHunting->city_id)
            <h6>{{$candidateHunting->city()->name}}</h6>
            @endisset
            @include('layouts.partials.select_ajax',array('param' => $candidateHunting->city_id,'name' => 'city_id','route' => '/api/city/uf'))
            {!! $errors->first('city_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-row mb-3">
            <div class="form-group ">
                {{ Form::label('Primeiro Emprego?') }}
                <input type="hidden" name="first_job" value="0">
                <input name="first_job" type="checkbox" class="" @if($candidateHunting->first_job==1) checked @endif value="1">                        
                {!! $errors->first('first_job', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="form-row mb-3">
            <div class="form-group ">
                {{ Form::label('Deseja trabalhar remoto?') }}
                <input type="hidden" name="remote" value="0">
                <input name="remote" type="checkbox" class="" @if($candidateHunting->remote==1) checked @endif value="1">                        
                {!! $errors->first('remote', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="form-row mb-3">
            <div class="form-group ">
                {{ Form::label('Tem Disponibilidade De Mudança?') }}
                <input type="hidden" name="move_out" value="0"> 
                <input name="move_out" type="checkbox" class="" @if($candidateHunting->move_out==1) checked @endif value="1"> 

                {!! $errors->first('move_out', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>       
        <div class="form-row mb-3">
            <div class="col-lg-9">
                <div class="form-group">         {{ Form::label('Nível de Inglês') }}
                    @include('layouts.partials.select',array('list' => $english_levels,'param' => $candidateHunting->english_level,'name' => 'english_level'))

                    {!! $errors->first('english_level', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>
        {{ Form::hidden('user_id', $candidateHunting->user_id, ['class' => 'form-control' . ($errors->has('user_id') ? ' is-invalid' : ''), 'placeholder' => 'User Id']) }}

        <div class="form-row mb-3">
            <div class="col-lg-9">
                <div class="form-group">
                    {{ Form::label('Genêro') }}
                    @include('layouts.partials.select',array('list' => $gender,'param' => $candidateHunting->gender_id,'name' => 'gender_id'))            
                </div>
            </div>
        </div>
        <div class="form-row mb-3">
            <div class="col-lg-9">
                <div class="form-group">
                    {{ Form::label('Raça') }}
                    @include('layouts.partials.select',array('list' => $race,'param' => $candidateHunting->race_id,'name' => 'race_id'))            
                </div>
            </div>
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Salvar</button>"
        @isset($candidateHunting->id)
        <a class="btn btn-success" href="{{ route('candidate-hunt.show',$candidateHunting->id) }}"><i class="fa fa-fw fa-eye"></i> Voltar a Exibição</a>
        @endisset
        <a class="btn btn-primary" href="{{ route('hunt.index') }}"> Retornar a Lista</a>
    </div>
</div>