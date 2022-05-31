<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('Escritorio') }}
               @include('layouts.partials.select',array('list' => $office,'param' => $user->office_id,'name' => 'office_id'))
            {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Funcao') }}
         @include('layouts.partials.select',array('list' => $role,'param' => $user->role_id,'name' => 'role_id'))
        </div>
    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>