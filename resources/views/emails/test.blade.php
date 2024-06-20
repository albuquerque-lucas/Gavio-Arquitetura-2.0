@component('mail::message')
# {{ $details['title'] }}

{{ $details['body'] }}

@component('mail::button', ['url' => 'https://example.com'])
Clique Aqui
@endcomponent

Obrigado,<br>
Gávio Arquitetura e Interiores
@endcomponent
