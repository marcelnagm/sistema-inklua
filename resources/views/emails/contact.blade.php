@component('mail::message')

<h1>E-mail de contato do site
</h1>

<p>Nome: {{$name}}
</p>
<p>
E-mail: {{$email}}
</p>
<p>
Tipo: {{$type}}
</p>
<p>
@if($type == 'empresa')
</p>
<p>
Empresa: {{$company}}
@endif
</p>
<p>
Mensagem:
{{$message}}
</p>

<hr>

<div class="small-text">
    <p>Por favor não responda esse e-mail. </p>
    <p class="emphasis">Em caso de dúvidas entre em contato com a gente.</p>
</div>
{{ config('app.name') }}

@endcomponent