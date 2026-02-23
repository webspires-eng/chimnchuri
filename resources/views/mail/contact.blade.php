<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>New Contact Inquiry</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #2D5A27;
            /* Dark green brand color from screenshot */
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .content {
            padding: 30px;
        }

        .field {
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .field-label {
            font-weight: bold;
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .field-value {
            font-size: 16px;
            color: #1a1a1a;
        }

        .footer {
            background-color: #f9f9f9;
            color: #888;
            padding: 20px;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>New inquiry</h1>
        </div>
        <div class="content">
            <div class="field">
                <div class="field-label">Full Name</div>
                <div class="field-value">{{ $data['full_name'] }}</div>
            </div>

            <div class="field">
                <div class="field-label">Email Address</div>
                <div class="field-value">{{ $data['email'] }}</div>
            </div>

            <div class="field">
                <div class="field-label">Subject</div>
                <div class="field-value">{{ $data['subject'] }}</div>
            </div>

            <div class="field" style="border-bottom: none;">
                <div class="field-label">Message</div>
                <div class="field-value" style="white-space: pre-wrap;">{{ $data['message'] }}</div>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>

</html>
