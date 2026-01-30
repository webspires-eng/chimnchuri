<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .container {
            background-color: #f9f9f9;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #4CAF50;
            margin: 0;
        }

        .content {
            background-color: white;
            padding: 25px;
            border-radius: 5px;
        }

        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }

        .button:hover {
            background-color: #45a049;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }

        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 10px;
            margin: 15px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
        </div>

        <div class="content">
            <h2>Hello {{ $user->name ?? 'User' }}!</h2>

            <p>We received a request to reset your password. Click the button below to create a new password:</p>

            <div style="text-align: center;">
                <a href="{{ url('reset-password/' . $token . '?email=' . $email) }}" class="button">
                    Reset Password
                </a>
            </div>

            <p>Or copy and paste this link into your browser:</p>
            <p style="word-break: break-all; background-color: #f5f5f5; padding: 10px; border-radius: 3px;">
                {{ url('reset-password/' . $token . '?email=' . $email) }}
            </p>

            <div class="warning">
                <strong>⚠️ Important:</strong> This password reset link will expire in 60 minutes.
            </div>

            <p>If you did not request a password reset, please ignore this email or contact support if you have
                concerns.</p>

            <p>Best regards,<br>
                The {{ config('app.name') }} Team</p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>This is an automated email, please do not reply.</p>
        </div>
    </div>
</body>

</html>