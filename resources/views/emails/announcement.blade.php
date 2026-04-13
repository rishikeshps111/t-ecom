<x-mail::message>
# Hello {{ $user->name }}

# {{ $subjectLine ?? 'Announcement' }}

{{ $messageBody }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
