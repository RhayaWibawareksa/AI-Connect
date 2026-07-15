@extends('layouts.app')

@section('title', 'Reset Password — AI-Connect')

@section('content')
<div class="container py-5">
    <div class="page-header">
        <div class="container">
            <h1>Reset Password</h1>
            <p>Masukkan password baru Anda.</p>
        </div>
    </div>

    <div class="form-container" style="max-width:640px; margin:0 auto;">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ url('/password/reset') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}" />
            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required class="form-control">
            </div>

            <div class="form-group mt-3">
                <label for="password">Password Baru</label>
                <input id="password" type="password" name="password" required class="form-control">
            </div>

            <div class="form-group mt-3">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required class="form-control">
            </div>

            <div class="form-group mt-3">
                <button class="btn-submit">Reset Password</button>
                <a href="{{ url('/login') }}" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
