@component('mail::message')

<h1>Olá, {{$position->user->name}}</h1>

<p>Recebemos a sua vaga para <strong>“{{$position->title}}”</strong>, agora nossa equipe vai analisar se está tudo certo, ok? </p>
<p>Pode deixar que nossa equipe em breve vai avisar você sobre as próximas etapas.  </p>
<br>
<hr>

<div class="small-text">
    <p>Por favor não responda esse e-mail. </p>
    <p class="emphasis">Em caso de dúvidas entre em contato com a gente.</p>
</div>

@endcomponent
