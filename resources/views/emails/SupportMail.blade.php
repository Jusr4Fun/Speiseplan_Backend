@component('mail::message')
# {{ $testMailData['title'] }}
Betreff: {{ $testMailData['subject']}}
<p>
{{$testMailData['body']}}</p>
Mit freundlichen Grüßen,<br>
{{ config('app.name') }}
@endcomponent
