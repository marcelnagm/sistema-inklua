@if ($errors->any())
     @foreach ($errors->all() as $error)
         <div>{{$error}}</div>
     @endforeach
 @endif
<div class="box box-info padding-1">
    <div class="box-body">
        <div class="form-row mb-3">
            <div class="form-group col-lg-6 ">
                {{ Form::label('Titulo') }}
                {{ Form::text('title', $candidate->title, ['class' => 'form-control' . ($errors->has('title') ? ' is-invalid' : ''), 'placeholder' => 'Title']) }}
                {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                    {{ Form::hidden('gid', $candidate->gid, ['class' => 'form-control' . ($errors->has('gid') ? ' is-invalid' : ''), 'placeholder' => 'Gid']) }}
                    {{ Form::hidden('status_id', $candidate->status_id, ['class' => 'form-control' . ($errors->has('status_id') ? ' is-invalid' : ''), 'placeholder' => 'Gid']) }}

                    {{ Form::label('Função') }}
                    @include('layouts.partials.select',array('list' => $role,'param' => $candidate->role_id,'name' => 'role_id'))

                    <a href="{{ route('role.create') }}">Não tem a função? Adicione aqui </a>   

                </div>
            </div>

        </div>
        <div class="form-row mb-3">
            <div class="form-group col-lg-6">
                {{ Form::label('Pretensão Salárial') }}
                {{ Form::text('payment', $candidate->payment, ['class' => 'form-control payment' . ($errors->has('payment') ? ' is-invalid' : ''), 'placeholder' => 'Payment', 'onkeyup' => 'mascaraMoeda(this, event)']) }}
                {!! $errors->first('payment', '<div class="invalid-feedback">:message</div>') !!}
            </div>            
            <div class="form-group col-lg-6">
                {{ Form::label('CID') }}
                {{ Form::text('CID', $candidate->CID, ['class' => 'form-control' . ($errors->has('CID') ? ' is-invalid' : ''), 'placeholder' => 'CID']) }}
                {!! $errors->first('CID', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
    </div>
    <h5 class="bold">Localização</h5 class="bold"><br>
    <div class="form-row mb-3">
        
        <div class="form-group col-lg-6">
            {{ Form::label('Estado de Residencia') }}
            @include('layouts.partials.select',array('list' => $states,'param' => $candidate->state_id,'name' => 'state_id'))            
        </div>
        <div class="form-group col-lg-6">
            {{ Form::label('Cidade') }}
            {{ Form::text('city', $candidate->city, ['class' => 'form-control' . ($errors->has('city') ? ' is-invalid' : ''), 'placeholder' => 'City']) }}
            {!! $errors->first('city', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="form-row mb-3">
        <div class="form-group col-lg-6">
            {{ Form::label('Deseja trabalhar remoto?') }}
            <input type="hidden" name="remote" value="0">
            <input name="remote" type="checkbox" class="" @if($candidate->remote==1) checked @endif value="1">                        
            {!! $errors->first('remote', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group col-lg-6 ">
            {{ Form::label('Tem Disponibilidade De Mudança?') }}
            <input type="hidden" name="move_out" value="0"> 
            <input name="move_out" type="checkbox" class="" @if($candidate->move_out==1) checked @endif value="1"> 

            {!! $errors->first('move_out', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="form-row mb-3">
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('Genêro') }}
                @include('layouts.partials.select',array('list' => $gender,'param' => $candidate->gender_id,'name' => 'gender_id'))            
            </div>
        </div>
    
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('Raça') }}
                @include('layouts.partials.select',array('list' => $race,'param' => $candidate->race_id,'name' => 'race_id'))            
            </div>
        </div>
    </div> 
                <input type="hidden" name="pcd" value="1">                
        <div class="form-row mb-3">
            <div class="col-lg-9">
                <div class="form-group">
                    {{ Form::label('Tipo de Deficiência') }}
                    @include('layouts.partials.select',array('list' => $pcd,'param' => $candidate->pcd_type_id,'name' => 'pcd_type_id'))
                    {!! $errors->first('pcd_type_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>
        <div class="form-group w-100 h-25">
            {{ Form::label('Detalhes  da deficiência') }}
            {{ Form::textarea('pcd_details', $candidate->pcd_details, ['class' => 'form-control' . ($errors->has('pcd_details') ? ' is-invalid' : ''), 'placeholder' => 'Pcd Details', 'rows' => 4]) }}
            {!! $errors->first('pcd_details', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Laudo Médico') }}
            {{ Form::file('pcd_report',['class' => 'form-control' . ($errors->has('pcd_report') ? ' is-invalid' : ''), 'placeholder' => 'Pcd Report']) }}
            {!! $errors->first('pcd_report', '<div class="invalid-feedback">:message</div>') !!}
            @isset($candidate->pcd_report)
            <a href='{{ route('candidate.pcd_report',$candidate->id) }}'>Laudo Medico </a>
            @endisset
        </div>    
    
        <div class="form-group w-100 h-25">
            {{ Form::label('Descrição do candidato') }}
            {{ Form::textarea('description', $candidate->description, ['class' => 'w-100 h-100' . ($errors->has('description') ? ' is-invalid' : ''), 'placeholder' => 'Description' ,'rows' => 5]) }}
            {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    
    <div class="form-row mb-3">
        <div class="form-group ">
            {{ Form::label('Nível técnico') }}
            {{ Form::text('tecnical_degree', $candidate->tecnical_degree, ['class' => 'form-control' . ($errors->has('tecnical_degree') ? ' is-invalid' : ''), 'placeholder' => 'Tecnical Degree']) }}
            {!! $errors->first('tecnical_degree', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="form-row mb-3">
        <div class="form-group ">
            {{ Form::label('Nível superior') }}
            {{ Form::text('superior_degree', $candidate->superior_degree, ['class' => 'form-control' . ($errors->has('superior_degree') ? ' is-invalid' : ''), 'placeholder' => 'Superior Degree']) }}
            {!! $errors->first('superior_degree', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="form-row mb-3">
        <div class="form-group ">
            {{ Form::label('Pós-graduação') }}
            {{ Form::text('spec_degree', $candidate->spec_degree, ['class' => 'form-control' . ($errors->has('spec_degree') ? ' is-invalid' : ''), 'placeholder' => 'Spec Degree']) }}
            {!! $errors->first('spec_degree', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="form-row mb-3"> 
        <div class="form-group ">
            {{ Form::label('MBA') }}
            {{ Form::text('mba_degree', $candidate->mba_degree, ['class' => 'form-control' . ($errors->has('mba_degree') ? ' is-invalid' : ''), 'placeholder' => 'Mba Degree']) }}
            {!! $errors->first('mba_degree', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="form-row mb-3">
        <div class="form-group ">
            {{ Form::label('Mestrado') }}
            {{ Form::text('master_degree', $candidate->master_degree, ['class' => 'form-control' . ($errors->has('master_degree') ? ' is-invalid' : ''), 'placeholder' => 'Master Degree']) }}
            {!! $errors->first('master_degree', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="form-row mb-3">
        <div class="form-group ">
            {{ Form::label('Doutorado') }}
            {{ Form::text('doctor_degree', $candidate->doctor_degree, ['class' => 'form-control' . ($errors->has('doctor_degree') ? ' is-invalid' : ''), 'placeholder' => 'Doctor Degree']) }}
            {!! $errors->first('doctor_degree', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="form-row mb-3">
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('Nível de Inglês') }}
                @include('layouts.partials.select',array('list' => $english_levels,'param' => $candidate->english_level,'name' => 'english_level'))

                {!! $errors->first('english_level', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>

    </div>
    <div class="form-row mb-3">
        <div class="form-group col-lg-6 ">
            {{ Form::label('Nome Completo') }}
            {{ Form::text('full_name', $candidate->full_name, ['class' => 'form-control' . ($errors->has('full_name') ? ' is-invalid' : ''), 'placeholder' => 'Full Name']) }}
            {!! $errors->first('full_name', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group col-lg-6 ">
            {{ Form::label('Endereço do CV') }}
            {{ Form::text('cv_url', $candidate->cv_url, ['class' => 'form-control' . ($errors->has('cv_url') ? ' is-invalid' : ''), 'placeholder' => 'Cv Url']) }}
            {!! $errors->first('cv_url', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    
    </div>
    <div class="form-row mb-3">
        <div class="form-group col-lg-6 ">
            {{ Form::label('Celular') }}
            {{ Form::text('cellphone', $candidate->cellphone, ['class' => 'form-control' . ($errors->has('cellphone') ? ' is-invalid' : ''), 'placeholder' => 'Cellphone','id' => 'cellphone']) }}
            {!! $errors->first('cellphone', '<div class="invalid-feedback">:message</div>') !!}
        </div>
            <div class="form-group col-lg-6 ">
            {{ Form::label('Email') }}
            {{ Form::text('email', $candidate->email, ['class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''), 'placeholder' => 'Email']) }}
            {!! $errors->first('email', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    
        
    </div>
    
    
    
        {{ Form::hidden('published_at', $candidate->published_at, ['class' => 'form-control' . ($errors->has('published_at') ? ' is-invalid' : ''), 'placeholder' => 'Published At']) }}
    
</div>
<div class="box-footer mt20 mb-4">
    <button type="submit" class="btn btn-primary">Salvar</button>
</div>
