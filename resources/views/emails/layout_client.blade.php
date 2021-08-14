@component('mail::message')
# Olá {{ $content->nome }}!
<p>Segue o link para recuperação de senha</p>
@component('mail::button', ['url' => $url, 'color' => 'green'])
Recuperar Senha
@endcomponent

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
