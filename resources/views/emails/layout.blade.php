@component('mail::message')
# Olá {{ $user->name }}!
<p>Cliente {{$content->nome}}, mandou a seguinte mensagem na intenção de frete <b>{{$content->id}}<b></p>
@component('mail::panel')
{{$contentDetails->descricao}}
@endcomponent
<p>para a cidade {{$content->nome}}</p>


@component('mail::button', ['url' => $url, 'color' => 'green'])
Acessar para responder
@endcomponent

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
