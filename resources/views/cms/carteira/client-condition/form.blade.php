<div class="box box-info padding-1">
    <div class="box-body">
        {{ Form::hidden('client_id', $clientCondition->client_id, ['class' => 'form-control' . ($errors->has('client_id') ? ' is-invalid' : ''), 'placeholder' => 'Client Id']) }}
        <div class="form-group">
            {{ Form::label('Tipo de Condição') }}
            @include('layouts.partials.select',array('list' => $condtions,'param' => $clientCondition->condition_id,'id' => 'condition_id','name' => 'condition_id','onchange' => 'change(this);'))
            {!! $errors->first('condition_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        @isset($clientCondition->condition_id)
        @if($clientCondition->condition()->first()->intervals == 1)
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
        <script>
            $(document).ready(function () {

                $('#cond').show('1');
            });


        </script>
        @endif
        @endisset
        <div class="form-group">
            {{ Form::label('Tipo de Taxa') }}

            <select name="brute" class="form-control" >
                <option value="">Selecione uma Opção</option>
                <option value="0" @isset($clientCondition->brute) @if($clientCondition->brute ==0) selected @endif @endisset >Bruta</option>
                <option value="1" @isset($clientCondition->brute) @if($clientCondition->brute ==1) selected @endif @endisset>Líquida</option>                
            </select>
            {!! $errors->first('brute', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Taxa em %') }}
            {{ Form::text('tax', $clientCondition->tax, ['class' => 'form-control' . ($errors->has('tax') ? ' is-invalid' : ''), 'placeholder' => 'Não incluia o %, apenas números']) }}
            {!! $errors->first('tax', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Garantia') }}
            {{ Form::text('guarantee', $clientCondition->guarantee, ['class' => 'form-control' . ($errors->has('guarantee') ? ' is-invalid' : ''), 'placeholder' => 'Garantia em dias']) }}
            {!! $errors->first('guarantee', '<div class="invalid-feedback">:message</div>') !!}
        </div>

        <div class="row" id="cond"        style="display: none;"                   >
            <div class="form-group col-lg-6">
                {{ Form::label('Valor Inicial') }}
                {{ Form::text('start_cond', $clientCondition->start_cond, ['class' => 'form-control' . ($errors->has('start_cond') ? ' is-invalid' : ''), 'placeholder' => 'Valor Inicial']) }}
                {!! $errors->first('start_cond', '<div class="invalid-feedback">:message</div>') !!}
            </div>
            <div class="form-group col-lg-6">
                {{ Form::label('Valor Final') }}
                {{ Form::text('end_cond', $clientCondition->end_cond, ['class' => 'form-control' . ($errors->has('end_cond') ? ' is-invalid' : ''), 'placeholder' => 'Valor Final']) }}
                {!! $errors->first('end_cond', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>               
        <input type="hidden" name="active" value="1">
    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
<script>
            function change(input) {

                console.log(input);
                var opt = "" + $("#" + input.id + " option:selected").text();

                if (opt.includes('inicial')) {
                    $('#cond').show('1');
                } else {
                    $('#cond').hide('1');
                }
            }

</script>