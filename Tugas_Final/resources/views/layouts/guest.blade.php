<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Leave Management') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Inter', sans-serif;
                background-color: white;
                margin: 0;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .login-container {
                width: 100%;
                max-width: 400px;
                padding: 0 20px;
            }
            
            .logo {
                text-align: center;
                margin-bottom: 40px;
            }
            
            .logo-icon {
                width: 64px;
                height: 64px;
                background-color: #111827;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 16px;
            }
            
            .logo-icon svg {
                width: 28px;
                height: 28px;
                color: white;
            }
            
            .logo h1 {
                font-size: 24px;
                font-weight: 600;
                color: #111827;
                margin: 0 0 4px 0;
            }
            
            .logo p {
                font-size: 14px;
                color: #6B7280;
                margin: 0;
            }
            
            .login-card {
                background: white;
                border: 1px solid #E5E7EB;
                border-radius: 12px;
                padding: 32px;
            }
            
            .form-group {
                margin-bottom: 20px;
            }
            
            .form-group label {
                display: block;
                font-size: 14px;
                font-weight: 500;
                color: #374151;
                margin-bottom: 6px;
            }
            
            .form-control {
                width: 100%;
                padding: 10px 12px;
                border: 1px solid #D1D5DB;
                border-radius: 6px;
                font-size: 14px;
                color: #111827;
                transition: border-color 0.15s ease;
            }
            
            .form-control:focus {
                outline: none;
                border-color: #4F46E5;
                box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            }
            
            .remember-me {
                display: flex;
                align-items: center;
                gap: 8px;
                margin-bottom: 24px;
            }
            
            .remember-me input {
                width: 16px;
                height: 16px;
                border-radius: 4px;
                border: 1px solid #D1D5DB;
            }
            
            .remember-me label {
                font-size: 14px;
                color: #6B7280;
                cursor: pointer;
            }
            
            .btn-login {
                width: 100%;
                background-color: #111827;
                color: white;
                border: none;
                border-radius: 6px;
                padding: 12px;
                font-size: 14px;
                font-weight: 500;
                cursor: pointer;
                transition: background-color 0.15s ease;
            }
            
            .btn-login:hover {
                background-color: #1F2937;
            }
            
            .forgot-password {
                text-align: center;
                margin-top: 16px;
            }
            
            .forgot-password a {
                font-size: 13px;
                color: #6B7280;
                text-decoration: none;
            }
            
            .forgot-password a:hover {
                color: #4F46E5;
            }
            
            .footer {
                text-align: center;
                margin-top: 32px;
                font-size: 12px;
                color: #9CA3AF;
            }
            
            .error-message {
                font-size: 13px;
                color: #DC2626;
                margin-top: 4px;
            }
        </style>
    </head>
    <body>
        <div class="login-container">
            <div class="logo">
                <div class="logo-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <h1>Leave Management</h1>
                <p>Sign in to your account</p>
            </div>

            <div class="login-card">
                <!-- Session Status -->
                @if (session('status'))
                    <div style="background: #DCFCE7; color: #166534; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 14px;">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" :value="old('email')" required autofocus class="form-control" placeholder="you@company.com">
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password" class="form-control" placeholder="••••••••">
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="remember-me">
                        <input id="remember_me" type="checkbox" name="remember">
                        <label for="remember_me">Remember me</label>
                    </div>

                    <button type="submit" class="btn-login">Sign In</button>

                    @if (Route::has('password.request'))
                        <div class="forgot-password">
                            <a href="{{ route('password.request') }}">Forgot your password?</a>
                        </div>
                    @endif
                </form>
            </div>

            <div class="footer">
                &copy; {{ date('Y') }} Leave Management System
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Auto focus email input
                const emailInput = document.getElementById('email');
                if (emailInput) emailInput.focus();
            });
        </script>
    </body>
</html>