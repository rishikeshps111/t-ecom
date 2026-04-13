<x-mail::message>
Hello {{ $user->name ?? 'User' }},

Your account has been **deactivated** by the administrator.

If you believe this was done by mistake or need more information, please contact our support team.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
