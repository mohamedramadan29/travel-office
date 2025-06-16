<x-mail::message>
# Hello {{ $clientname }}

{{ $replayMessage }}

<x-mail::button :url="config('app.url')">
Visit Our Website
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
