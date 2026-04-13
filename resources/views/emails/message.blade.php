<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $subject ?? 'New Message from Admin' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background-color: #0d6efd;
            /* Bootstrap primary color */
            color: #ffffff;
            padding: 20px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }

        .email-body {
            padding: 20px;
            color: #333333;
            font-size: 16px;
            line-height: 1.5;
        }

        .email-footer {
            background-color: #f1f3f6;
            color: #555555;
            padding: 15px 20px;
            font-size: 14px;
            text-align: center;
        }

        .email-footer a {
            color: #0d6efd;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="email-container">

        {{-- Header --}}
        <div class="email-header">
            Admin Notification
        </div>

        {{-- Body --}}
        <div class="email-body">
            <p>Hello <strong>{{ $user->company_name }}</strong>,</p>

            <p>{!! $messageText !!}</p>

            <p>Thank you for your attention.</p>
        </div>

        {{-- Footer --}}
        <div class="email-footer">
            <p>Regards,<br>Admin Team</p>
            <p><a href="{{ config('app.url') }}">{{ config('app.name') }}</a></p>
        </div>

    </div>
</body>

</html>