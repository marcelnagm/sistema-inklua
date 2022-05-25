@component('mail::message')
# E-mail de contato do site

Nome: {{$name}}

E-mail: {{$email}}

Tipo: {{$type}}

@if($type == 'empresa')

Empresa: {{$company}}
@endif

Mensagem:
{{$message}}

<br>
{{ config('app.name') }}
@endcomponent
