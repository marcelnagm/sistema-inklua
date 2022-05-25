@component('mail::message')

<h1>Olá, {{$position->user->name}}</h1>

<p>A sua vaga para <strong>“{{$position->title}}”</strong> foi aprovada! Clique no botão abaixo e realize o pagamento.  </p>

@component('mail::button', ['url' => $url, 'color' => 'success'])
Realizar pagamento
@endcomponent

<p>Se tiver problemas, copie e cole o link abaixo em outra janela do seu navegador:  </p>
<div class="payment-link">
    <a href="{{$url}}" class="emphasis">{{$url}}</a>
</div>
<br>
<p>Ou se preferir, acesse o site da Inklua, faça o login e procure pela opção "Em espera" na página minhas vagas e selecione a vaga para realizar o pagamento. </p>
<br>
<hr>

<div class="small-text">
    <p>Por favor não responda esse e-mail. </p>
    <p class="emphasis">Em caso de dúvidas entre em contato com a gente.</p>
</div>

@endcomponent
