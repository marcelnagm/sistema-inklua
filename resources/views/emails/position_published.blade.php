@component('mail::message')

<h1>Olá, {{$position->user->name}}</h1>

<p>A sua vaga para <strong>“{{$position->title}}”</strong> foi publicada! A partir de agora os candidatos já podem acessá-la e se candidatar. </p>

@component('mail::button', ['url' => $url, 'color' => 'success'])
Visualizar vaga
@endcomponent

<p>Esperamos que você encontre o profissional que procura!</p>
<br>
<hr>

<div class="small-text">
    <p>Por favor não responda esse e-mail. </p>
    <p class="emphasis">Em caso de dúvidas entre em contato com a gente.</p>
</div>

@endcomponent
