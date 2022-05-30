<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('Escritorio') }}
               @include('layouts.partials.select',array('list' => $office,'param' => $user->office_id,'name' => 'office_id'))
            {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Lider') }}
             <input type="hidden" name="role_id" value="0">
                <input name="role_id" type="checkbox" class="" @if($user->role_id==1) checked @endif value="1">                        
        </div>
    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>