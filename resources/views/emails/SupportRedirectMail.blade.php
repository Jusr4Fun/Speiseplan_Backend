@component('mail::message')
# {{ $testMailData['title'] }}
Anfrage von : {{ $testMailData['email']}}<br>
Betreff: {{ $testMailData['subject']}}
<p>
{{$testMailData['body']}}</p><br>
Mit freundlichen Grüßen,<br>
{{ config('app.name') }}
@endcomponent
