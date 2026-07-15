<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru — AI-Connect</title>
    
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
            padding: 2rem 0;
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
            max-width: 460px;
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
            <p class="auth-subtitle">Daftar akun baru dan bergabung dengan komunitas</p>
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

        <form action="{{ url('/register') }}" method="POST">
            @csrf

            <!-- Nama Lengkap -->
            <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <div class="input-group-custom">
                    <span class="input-group-icon"><i class="bi bi-person"></i></span>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        class="form-control-custom" 
                        placeholder="Cth: Alex Dev" 
                        value="{{ old('name') }}" 
                        required
                    >
                </div>
            </div>

            <!-- Username -->
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <div class="input-group-custom">
                    <span class="input-group-icon"><i class="bi bi-hash"></i></span>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        class="form-control-custom" 
                        placeholder="Cth: alex_dev" 
                        value="{{ old('username') }}" 
                        required
                    >
                </div>
            </div>

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
                <label for="password" class="form-label">Password</label>
                <div class="input-group-custom">
                    <span class="input-group-icon"><i class="bi bi-lock"></i></span>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control-custom" 
                        placeholder="Minimal 6 karakter" 
                        required
                    >
                </div>
            </div>

            <!-- Konfirmasi Password -->
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <div class="input-group-custom">
                    <span class="input-group-icon"><i class="bi bi-shield-check"></i></span>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="form-control-custom" 
                        placeholder="Ulangi password" 
                        required
                    >
                </div>
            </div>

            <button type="submit" class="btn btn-auth">
                <i class="bi bi-person-plus me-2"></i> Buat Akun Baru
            </button>
        </form>

        <div class="auth-footer">
            Sudah punya akun? <a href="{{ url('/login') }}" class="auth-link">Masuk di sini</a>
        </div>

    </div>
</div>

</body>
</html>
