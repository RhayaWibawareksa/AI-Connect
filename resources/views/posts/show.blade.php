{{-- Halaman Detail Post --}}

@extends('layouts.app')

@section('title', $post->title . ' — AI-Connect')

@section('styles')
<style>
    .post-detail-header {
        background: linear-gradient(135deg, #0f0f1a 0%, #1a1a2e 50%, #16213e 100%);
        color: #fff;
        padding: 2rem 0;
        margin-bottom: 2rem;
    }

    .post-detail-header h1 {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 1rem;
        line-height: 1.3;
    }

    .post-meta {
        display: flex;
        align-items: center;
        gap: 2rem;
        flex-wrap: wrap;
        font-size: 0.95rem;
        color: #cbd5e1;
    }

    .post-meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .post-content-container {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 2rem;
        margin-bottom: 3rem;
    }

    @media (max-width: 1024px) {
        .post-content-container {
            grid-template-columns: 1fr;
        }
    }

    .post-body-section {
        background: #ffffff;
        border: 1px solid #e8eaf0;
        border-radius: 14px;
        padding: 2rem;
        line-height: 1.8;
        color: #475569;
        font-size: 1rem;
    }

    .post-cover-image {
        width: 100%;
        max-height: 420px;
        object-fit: cover;
        border-radius: 14px;
        margin-bottom: 1.5rem;
        display: block;
    }

    .post-body-section p {
        margin-bottom: 1rem;
    }

    .post-body-section h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        margin: 2rem 0 1rem;
    }

    .post-body-section code {
        background: #f1f5f9;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        font-family: 'Courier New', monospace;
        color: #e11d48;
        font-size: 0.9em;
    }

    .post-body-section pre {
        background: #1e293b;
        color: #e2e8f0;
        padding: 1rem;
        border-radius: 8px;
        overflow-x: auto;
        margin: 1rem 0;
        font-family: 'Courier New', monospace;
    }

    .post-actions {
        display: flex;
        gap: 1rem;
        margin: 2rem 0;
        padding: 1.5rem;
        background: #f8f9ff;
        border-radius: 12px;
        flex-wrap: wrap;
    }

    .post-actions button,
    .post-actions a {
        padding: 0.6rem 1.2rem;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        background: #fff;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .post-actions button:hover,
    .post-actions a:hover {
        background: #6366f1;
        color: #fff;
        border-color: #6366f1;
    }

    .sidebar-widget {
        background: #ffffff;
        border: 1px solid #e8eaf0;
        border-radius: 14px;
        padding: 1.5rem;
        margin-bottom: 1rem;
    }

    .widget-title {
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .author-card {
        text-align: center;
    }

    .author-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        margin: 0 auto 1rem;
        border: 3px solid #6366f1;
        object-fit: cover;
    }

    .author-name {
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .author-role {
        font-size: 0.85rem;
        color: #94a3b8;
    }

    .comments-section {
        margin-top: 3rem;
        background: #ffffff;
        border: 1px solid #e8eaf0;
        border-radius: 14px;
        padding: 2rem;
    }

    .comments-section h2 {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1.5rem;
    }

    .comment-item {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .comment-item:last-child {
        border-bottom: none;
    }

    .comment-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        flex-shrink: 0;
        object-fit: cover;
    }

    .comment-body {
        flex: 1;
    }

    .comment-meta {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
        font-size: 0.85rem;
    }

    .comment-author {
        font-weight: 700;
        color: #1e293b;
    }

    .comment-time {
        color: #94a3b8;
    }

    .comment-text {
        color: #475569;
        line-height: 1.6;
    }

    .comment-form {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #e8eaf0;
    }

    .comment-form textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.95rem;
        font-family: inherit;
        resize: vertical;
        min-height: 100px;
        margin-bottom: 1rem;
    }

    .comment-form textarea:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .comment-form button {
        background: #6366f1;
        color: #fff;
        border: none;
        padding: 0.6rem 1.5rem;
        border-radius: 20px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }

    .comment-form button:hover {
        background: #4f46e5;
    }
</style>
@endsection

@section('content')

<div class="post-detail-header">
    <div class="container">
        <h1>{{ $post->title }}</h1>
        <div class="post-meta">
            <div class="post-meta-item">
                <img
                    src="https://ui-avatars.com/api/?name={{ urlencode($post->user->name ?? 'Unknown') }}&background=6366f1&color=fff&size=32"
                    alt="{{ $post->user->name ?? 'Unknown' }}"
                    style="width: 32px; height: 32px; border-radius: 50%;"
                >
                <span>{{ $post->user->name ?? 'Unknown' }}</span>
            </div>
            @if ($post->category)
                <div class="post-meta-item">
                    <i class="bi bi-tag"></i> {{ $post->category->name }}
                </div>
            @endif
            <div class="post-meta-item">
                <i class="bi bi-calendar"></i> {{ $post->created_at->format('d M Y') }}
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="post-content-container">
        {{-- Konten Utama --}}
        <div>
            {{-- Tombol Aksi --}}
            <div class="post-actions">
                <button type="button" onclick="window.handleVote(this, {{ $post->id }}, 'up')">
                    <i class="bi bi-arrow-up-circle"></i> Upvote (<span id="detail-vote-count-{{ $post->id }}">{{ $post->votes ?? 0 }}</span>)
                </button>
                <button type="button" onclick="window.handleVote(this, {{ $post->id }}, 'down')">
                    <i class="bi bi-arrow-down-circle"></i> Downvote
                </button>
                <button type="button" onclick="window.handleBookmark(this, {{ $post->id }})">
                    <i class="bi bi-bookmark"></i> Simpan
                </button>
                <button type="button" onclick="window.sharePost('{{ route('posts.show', $post->id) }}')">
                    <i class="bi bi-share"></i> Bagikan
                </button>
                <button type="button" class="text-danger" onclick="window.reportPost({{ $post->id }})">
                    <i class="bi bi-flag"></i> Laporkan
                </button>
            </div>

            {{-- Konten Postingan --}}
            <div class="post-body-section">
            @if ($post->image_url)
                @php
                    $imageSrc = preg_match('/^https?:\/\//', $post->image_url)
                        ? $post->image_url
                        : \Illuminate\Support\Facades\Storage::disk('public')->url(ltrim($post->image_url, '/'));
                @endphp
                <img src="{{ $imageSrc }}" alt="Gambar Post" class="post-cover-image">
            @endif
            </div>

            {{-- Bagian Komentar --}}
            <div class="comments-section">
                <h2>Komentar ({{ $post->comments->count() }})</h2>

                @forelse ($post->comments as $comment)
                    <div class="comment-item">
                        <img
                            src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name ?? 'Unknown') }}&background=06b6d4&color=fff&size=40"
                            alt="{{ $comment->user->name ?? 'Unknown' }}"
                            class="comment-avatar"
                        >
                        <div class="comment-body">
                            <div class="comment-meta">
                                <span class="comment-author">{{ $comment->user->name ?? 'Unknown' }}</span>
                                <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="comment-text">{{ $comment->content }}</p>
                        </div>
                    </div>
                @empty
                    <p style="color: #94a3b8; text-align: center; padding: 2rem;">
                        Belum ada komentar. Jadilah yang pertama berkomentar!
                    </p>
                @endforelse

                {{-- Form Tambah Komentar --}}
                <div class="comment-form">
                    <form action="{{ url('/posts/' . $post->id . '/comments') }}" method="POST">
                        @csrf
                        <textarea
                            name="content"
                            placeholder="Tulis komentar kamu di sini..."
                            required
                        ></textarea>
                        <button type="submit">
                            <i class="bi bi-send"></i> Kirim Komentar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div>
            {{-- Widget Penulis --}}
            <div class="sidebar-widget author-card">
                <div class="widget-title" style="justify-content: center;">
                    <i class="bi bi-person-circle"></i> Penulis
                </div>
                <img
                    src="https://ui-avatars.com/api/?name={{ urlencode($post->user->name ?? 'Unknown') }}&background=6366f1&color=fff&size=80"
                    alt="{{ $post->user->name ?? 'Unknown' }}"
                    class="author-avatar"
                >
                <div class="author-name">{{ $post->user->name ?? 'Unknown' }}</div>
                <div class="author-role">{{ $post->user->username ?? 'Member' }}</div>
            </div>

            {{-- Widget Statistik --}}
            <div class="sidebar-widget">
                <div class="widget-title">
                    <i class="bi bi-bar-chart"></i> Statistik
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; text-align: center;">
                    <div>
                        <div style="font-size: 1.5rem; font-weight: 700; color: #6366f1;">
                            {{ $post->votes ?? 0 }}
                        </div>
                        <div style="font-size: 0.8rem; color: #94a3b8;">Votes</div>
                    </div>
                    <div>
                        <div style="font-size: 1.5rem; font-weight: 700; color: #06b6d4;">
                            {{ $post->comments->count() }}
                        </div>
                        <div style="font-size: 0.8rem; color: #94a3b8;">Komentar</div>
                    </div>
                </div>
            </div>

            {{-- Widget Link Kembali --}}
            <div class="sidebar-widget" style="text-align: center;">
                <a href="{{ url('/dashboard') }}" class="btn btn-light w-100" style="
                    display: inline-block;
                    padding: 0.75rem;
                    background: #f8f9ff;
                    color: #6366f1;
                    border: 1px solid #6366f1;
                    border-radius: 8px;
                    text-decoration: none;
                    font-weight: 600;
                    transition: all 0.2s;
                ">
                    <i class="bi bi-arrow-left me-2"></i> Kembali ke Feed
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
