<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Sign In | {{ $settings->restaurant_name ?? 'Chimnchurri' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admin Panel" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            background: #0a0a0a;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Animated background */
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(ellipse at 20% 50%, rgba(34, 197, 94, 0.08) 0%, transparent 50%),
                        radial-gradient(ellipse at 80% 20%, rgba(34, 197, 94, 0.05) 0%, transparent 50%),
                        radial-gradient(ellipse at 50% 80%, rgba(34, 197, 94, 0.03) 0%, transparent 50%);
            animation: bgFloat 20s ease-in-out infinite;
            z-index: 0;
        }

        @keyframes bgFloat {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(1deg); }
            66% { transform: translate(-20px, 20px) rotate(-1deg); }
        }

        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
            padding: 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 24px;
            padding: 48px 40px;
            backdrop-filter: blur(40px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4),
                        inset 0 1px 0 rgba(255, 255, 255, 0.05);
            animation: cardSlideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes cardSlideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo-wrapper {
            text-align: center;
            margin-bottom: 36px;
        }

        .logo-wrapper img {
            max-height: 80px;
            max-width: 200px;
            object-fit: contain;
            filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.3));
            transition: transform 0.3s ease;
        }

        .logo-wrapper img:hover {
            transform: scale(1.05);
        }

        .logo-fallback {
            font-size: 28px;
            font-weight: 900;
            color: #22c55e;
            letter-spacing: -0.5px;
        }

        .login-title {
            font-size: 26px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 6px;
            letter-spacing: -0.5px;
        }

        .login-subtitle {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.4);
            margin-bottom: 32px;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            color: #ffffff;
            font-size: 15px;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-input::placeholder {
            color: rgba(255, 255, 255, 0.2);
        }

        .form-input:focus {
            border-color: rgba(34, 197, 94, 0.5);
            background: rgba(255, 255, 255, 0.06);
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.08),
                        0 0 20px rgba(34, 197, 94, 0.05);
        }

        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.3);
            cursor: pointer;
            font-size: 13px;
            padding: 4px;
            transition: color 0.2s;
        }

        .toggle-password:hover {
            color: rgba(255, 255, 255, 0.6);
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            border: none;
            border-radius: 14px;
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 8px;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.3px;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(34, 197, 94, 0.3);
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 20px;
            color: #fca5a5;
            font-size: 13px;
            line-height: 1.5;
            animation: shake 0.4s ease;
        }

        .alert-error ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .alert-error li {
            padding: 2px 0;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .footer-text {
            text-align: center;
            margin-top: 24px;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.2);
        }

        /* Floating particles */
        .particle {
            position: fixed;
            width: 2px;
            height: 2px;
            background: rgba(34, 197, 94, 0.3);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        .particle:nth-child(1) { top: 20%; left: 10%; animation: float 6s ease-in-out infinite; }
        .particle:nth-child(2) { top: 60%; left: 85%; animation: float 8s ease-in-out infinite 1s; width: 3px; height: 3px; }
        .particle:nth-child(3) { top: 80%; left: 30%; animation: float 7s ease-in-out infinite 2s; }
        .particle:nth-child(4) { top: 10%; left: 70%; animation: float 9s ease-in-out infinite 0.5s; width: 4px; height: 4px; opacity: 0.5; }
        .particle:nth-child(5) { top: 40%; left: 50%; animation: float 10s ease-in-out infinite 3s; }

        @keyframes float {
            0%, 100% { transform: translateY(0) translateX(0); opacity: 0.3; }
            50% { transform: translateY(-30px) translateX(15px); opacity: 0.8; }
        }

        @media (max-width: 480px) {
            .login-card { padding: 36px 24px; border-radius: 20px; }
            .login-title { font-size: 22px; }
        }
    </style>
</head>

<body>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>

    <div class="login-container">
        <div class="login-card">
            <div class="logo-wrapper">
                @if(!empty($settings->restaurant_logo))
                    <img src="{{ asset($settings->restaurant_logo) }}" alt="{{ $settings->restaurant_name ?? 'Chimnchurri' }}">
                @else
                    <span class="logo-fallback">{{ $settings->restaurant_name ?? 'Chimnchurri' }}</span>
                @endif
            </div>

            <h1 class="login-title">Welcome back</h1>
            <p class="login-subtitle">Sign in to your admin dashboard</p>

            @if (session('error'))
                <div class="alert-error">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-input"
                        placeholder="admin@chimnchurri.com" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" class="form-input"
                            placeholder="Enter your password" required>
                        <button type="button" class="toggle-password" onclick="togglePassword()">Show</button>
                    </div>
                </div>

                <button type="submit" class="btn-login">Sign In</button>
            </form>

            <p class="footer-text">&copy; {{ date('Y') }} {{ $settings->restaurant_name ?? 'Chimnchurri' }}. Admin Panel.</p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const btn = document.querySelector('.toggle-password');
            if (input.type === 'password') {
                input.type = 'text';
                btn.textContent = 'Hide';
            } else {
                input.type = 'password';
                btn.textContent = 'Show';
            }
        }
    </script>
</body>

</html>
