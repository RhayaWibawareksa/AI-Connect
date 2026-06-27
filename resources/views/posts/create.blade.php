{{-- Halaman Buat Post Baru --}}

@extends('layouts.app')

@section('title', 'Buat Postingan Baru — AI-Connect')

@section('styles')
<style>
    .form-container {
        background: #ffffff;
        border: 1px solid #e8eaf0;
        border-radius: 14px;
        padding: 2rem;
        max-width: 800px;
        margin: 0 auto;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.95rem;
        font-family: inherit;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 300px;
    }

    .btn-submit {
        background: #6366f1;
        color: #fff;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 20px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s, transform 0.1s;
    }

    .btn-submit:hover {
        background: #4f46e5;
        transform: translateY(-1px);
    }

    .btn-cancel {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
        padding: 0.75rem 2rem;
        border-radius: 20px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-block;
        margin-left: 0.5rem;
    }

    .btn-cancel:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    .page-header {
        background: linear-gradient(135deg, #0f0f1a 0%, #1a1a2e 50%, #16213e 100%);
        color: #fff;
        padding: 2rem 0;
        margin-bottom: 2rem;
        text-align: center;
    }

    .page-header h1 {
        font-size: 1.8rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }

    .page-header p {
        color: #94a3b8;
        font-size: 0.95rem;
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <div class="container">
        <h1>✍️ Buat Postingan Baru</h1>
        <p>Bagikan pengetahuan dan pengalaman AI-mu kepada komunitas</p>
    </div>
</div>

<div class="container py-5">
    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('/posts') }}" method="POST" class="form-container">
        @csrf

        <div class="form-group">
            <label for="title">Judul Postingan</label>
            <input
                type="text"
                id="title"
                name="title"
                placeholder="Cth: Implementasi Algoritma XGBoost dari Nol"
                value="{{ old('title') }}"
                required
            >
            @error('title')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="category_id">Kategori</label>
            <select id="category_id" name="category_id">
                <option value="">-- Pilih Kategori (Opsional) --</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="content">Konten Postingan</label>
            <textarea
                id="content"
                name="content"
                placeholder="Tulis penjelasan detail, kode, atau tutorial di sini. Gunakan Markdown untuk format."
                required
            >{{ old('content') }}</textarea>
            @error('content')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <button type="submit" class="btn-submit">
                <i class="bi bi-check-circle me-2"></i> Publish Postingan
            </button>
            <a href="{{ url('/dashboard') }}" class="btn-cancel">
                <i class="bi bi-x-circle me-1"></i> Batalkan
            </a>
        </div>
    </form>
</div>

@endsection
