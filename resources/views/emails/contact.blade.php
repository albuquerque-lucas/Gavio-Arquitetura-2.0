@component('mail::message')
# Contato do Site

**Nome:** {{ $details['name'] }}

**Email:** [{{ $details['email'] }}](mailto:{{ $details['email'] }})

**Assunto:** {{ $details['subject'] }}

**Mensagem:**

{{ $details['message'] }}

@endcomponent
