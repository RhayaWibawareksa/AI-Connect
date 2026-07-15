@extends('layouts.app')

@section('title', 'Reset Password — AI-Connect')

@section('content')
<div class="container py-5">
    <div class="page-header">
        <div class="container">
            <h1>Minta Link Reset Password</h1>
            <p>Masukkan email Anda, kami akan mengirimkan link untuk mereset password.</p>
        </div>
    </div>

    <div class="form-container" style="max-width:640px; margin:0 auto;">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
n                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @if (session('reset_url'))
                <div class="alert" style="background:#eef7ff;border:1px solid #cfe9ff;color:#064e8a;">
                    <p class="mb-0">Link reset (pengujian): <a href="{{ session('reset_url') }}">{{ session('reset_url') }}</a></p>
                </div>
            @endif
        @endif

        <form method="POST" action="{{ url('/password/email') }}">
            @csrf
            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required class="form-control">
            </div>
            <div class="form-group mt-3">
                <button class="btn-submit">Kirim Link Reset</button>
                <a href="{{ url('/login') }}" class="btn-cancel">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
