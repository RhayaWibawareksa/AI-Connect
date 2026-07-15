<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — AI-Connect</title>
    
    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --ai-primary: #6366f1;
            --ai-accent: #06b6d4;
            --ai-bg: #09090e;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #09090e 0%, #121224 50%, #080f1d 100%);
            color: #e2e8f0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 70%);
            top: -100px;
            left: -100px;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.12) 0%, transparent 70%);
            bottom: -100px;
            right: -100px;
            pointer-events: none;
        }

        .auth-container {
            width: 100%;
            max-width: 440px;
            padding: 15px;
            z-index: 10;
        }

        .auth-card {
            background: rgba(30, 30, 50, 0.45);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 12px 40px 0 rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .auth-logo {
            font-size: 1.8rem;
            font-weight: 800;
            color: #fff;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .auth-logo .logo-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--ai-accent);
            display: inline-block;
            box-shadow: 0 0 10px var(--ai-accent);
        }

        .auth-subtitle {
            color: #94a3b8;
            font-size: 0.88rem;
            margin-bottom: 2rem;
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #cbd5e1;
            margin-bottom: 0.5rem;
        }

        .input-group-custom {
            position: relative;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            transition: all 0.2s;
        }

        .input-group-custom:focus-within {
            border-color: var(--ai-primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25);
            background: rgba(255, 255, 255, 0.08);
        }

        .input-group-icon {
            padding: 0.75rem 1rem;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-control-custom {
            background: none;
            border: none;
            color: #fff;
            padding: 0.75rem 1rem 0.75rem 0;
            width: 100%;
            font-size: 0.95rem;
        }

        .form-control-custom:focus {
            outline: none;
            box-shadow: none;
        }

        .form-control-custom::placeholder {
            color: #64748b;
        }

        .btn-auth {
            background: linear-gradient(135deg, var(--ai-primary) 0%, #4f46e5 100%);
            border: none;
            border-radius: 10px;
            color: #fff;
            padding: 0.8rem;
            font-weight: 600;
            font-size: 0.95rem;
            width: 100%;
            margin-top: 1.5rem;
            transition: all 0.2s;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
        }

        .btn-auth:hover {
            background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.5);
        }

        .auth-footer {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.85rem;
            color: #94a3b8;
        }

        .auth-link {
            color: var(--ai-accent);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .auth-link:hover {
            color: #0891b2;
            text-decoration: underline;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.5rem 0;
            color: #64748b;
            font-size: 0.8rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
        }

        .btn-oauth {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.05) 100%);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            color: #e2e8f0;
            padding: 0.75rem;
            font-weight: 500;
            font-size: 0.9rem;
            width: 100%;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            cursor: pointer;
        }

        .btn-oauth:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.12) 0%, rgba(255, 255, 255, 0.08) 100%);
            border-color: rgba(255, 255, 255, 0.25);
            transform: translateY(-1px);
        }

        .btn-google {
            background: linear-gradient(135deg, #4285f4 0%, #357ae8 100%);
            border: none;
            color: #fff;
        }

        .btn-google:hover {
            background: linear-gradient(135deg, #357ae8 0%, #1a67d8 100%);
            box-shadow: 0 4px 12px rgba(66, 133, 244, 0.4);
        }
    </style>
</head>
<body>

<div class="auth-container">
    <div class="auth-card">
        
        <div class="text-center">
            <a href="/" class="auth-logo">
                <span class="logo-dot"></span>
                AI<span style="color: var(--ai-accent);">Connect</span>
            </a>
            <p class="auth-subtitle">Masuk ke akun Anda untuk mulai berdiskusi</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger py-2 px-3 mb-4" style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; font-size: 0.82rem; border-radius: 10px;">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success py-2 px-3 mb-4" style="background: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34, 197, 94, 0.3); color: #86efac; font-size: 0.82rem; border-radius: 10px;">
                <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            </div>
        @endif

        <form action="{{ url('/login') }}" method="POST">
            @csrf

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Alamat Email</label>
                <div class="input-group-custom">
                    <span class="input-group-icon"><i class="bi bi-envelope"></i></span>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-control-custom" 
                        placeholder="nama@email.com" 
                        value="{{ old('email') }}" 
                        required
                    >
                </div>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label for="password" class="form-label mb-0">Password</label>
                    {{-- Tambahan link Lupa Password --}}
                    <a href="{{ url('/password/reset') }}" class="auth-link" style="font-size: 0.75rem;">Lupa password?</a>
                </div>
                <div class="input-group-custom">
                    <span class="input-group-icon"><i class="bi bi-lock"></i></span>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control-custom" 
                        placeholder="••••••••" 
                        required
                    >
                </div>
            </div>

            <!-- Remember Me -->
            <div class="mb-3 form-check d-flex align-items-center gap-2 ps-0">
                <input 
                    type="checkbox" 
                    class="form-check-input ms-0 mt-0" 
                    id="remember" 
                    name="remember"
                    style="background-color: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.2); cursor: pointer;"
                >
                <label class="form-check-label text-muted" for="remember" style="font-size: 0.82rem; cursor: pointer;">Ingat Saya</label>
            </div>

            <button type="submit" class="btn btn-auth">
                <i class="bi bi-box-arrow-in-right me-2"></i> Masuk Sekarang
            </button>
        </form>

        <div class="divider">atau</div>

        <!-- Google OAuth Button -->
        <a href="{{ route('auth.google') }}" class="btn btn-oauth btn-google" style="text-decoration: none;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#ffffff"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#ffffff"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#ffffff"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#ffffff"/>
            </svg>
            Masuk dengan Google
        </a>

        <div class="auth-footer">
            Belum punya akun? <a href="{{ url('/register') }}" class="auth-link">Daftar di sini</a>
        </div>

    </div>
</div>

</body>
</html>