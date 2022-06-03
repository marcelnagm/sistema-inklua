<div class="box box-info padding-1">
    <div class="box-body">


        <div class="form-row">
            <div class="form-group col-lg-6">
                {{ Form::label('Nome Fantasia') }}
                {{ Form::text('fantasy_name', $client->fantasy_name, ['class' => 'form-control' . ($errors->has('fantasy_name') ? ' is-invalid' : ''), 'placeholder' => 'Nome Fantasia']) }}
                {!! $errors->first('fantasy_name', '<div class="invalid-feedback">:message</div>') !!}
            </div>
            <div class="form-group col-lg-6">
                {{ Form::label('Razão Social') }}
                {{ Form::text('formal_name', $client->formal_name, ['class' => 'form-control' . ($errors->has('formal_name') ? ' is-invalid' : ''), 'placeholder' => 'Razão Social']) }}
                {!! $errors->first('formal_name', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-lg-6">
              
                {{ Form::label('CNPJ') }}
                {{ Form::text('cnpj', $client->cnpj, ['class' => 'form-control' . ($errors->has('cnpj') ? ' is-invalid' : ''), 'placeholder' => 'Cnpj']) }}
                {!! $errors->first('cnpj', '<div class="invalid-feedback">:message</div>') !!}       
            </div>
            
            <div class="form-group col-lg-6">
                {{ Form::label('Setor / Área') }}
                {{ Form::text('sector', $client->sector, ['class' => 'form-control' . ($errors->has('sector') ? ' is-invalid' : ''), 'placeholder' => 'Setor / Área']) }}
                {!! $errors->first('sector', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-lg-6">
                {{ Form::label('Local') }}
                {{ Form::text('local_label', $client->local_label, ['class' => 'form-control' . ($errors->has('local_label') ? ' is-invalid' : ''), 'placeholder' => 'Local']) }}
                {!! $errors->first('local_label', '<div class="invalid-feedback">:message</div>') !!}
            </div>
            <div class="form-group col-lg-6">
                {{ Form::label('Ativo') }}
                 <select name="active" class="form-control" >
                <option value="">Selecione uma Opção</option>
                <option value="0" @isset($client->active) @if($client->active ==0) selected @endif @endisset >Inativo</option>
                <option value="1" @isset($client->active) @if($client->active ==1) selected @endif @endisset>Ativo</option>                
            </select>            
             
                {!! $errors->first('active', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-lg-6 ">
            <div class="form-group">
                {{ Form::label('Estado ') }}
                @include('layouts.partials.select',array('list' => $states,'param' => $client->state_id,'id' => 'state_id','name' => 'state_id','onchange' => 'depend("state_id");'))
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('Observações') }}
            {{ Form::textarea('obs', $client->obs, ['class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''), 'placeholder' => 'Observações','id' => 'desc']) }}
            {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Salvar</button>
    </div>
</div>