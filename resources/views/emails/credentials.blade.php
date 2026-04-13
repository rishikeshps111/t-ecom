<x-mail::message>
# Hello {{ $user->name }},

Your account has been created/updated. Here are your login credentials:

**User Name:** {{ $user->user_name }}  
**Email:** {{ $user->email }}  
**Password:** {{ $password }}

<x-mail::button :url="url('/admin/login')">
Login
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
