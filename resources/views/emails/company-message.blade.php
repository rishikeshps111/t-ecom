<x-mail::message>
# Hello {{ $company->company_name }},

{{ $messageText  }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
