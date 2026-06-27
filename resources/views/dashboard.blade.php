{{--
|=============================================================================
| HALAMAN FEED PUBLIK: resources/views/dashboard.blade.php
|=============================================================================
| Halaman utama yang diakses pengguna setelah login.
| Meng-extend layout induk `layouts.app`.
|
| Komponen:
|   - Feed postingan (kartu 2 kolom: konten + sidebar)
|   - Card post: avatar, kategori, isi, link source code, upvote/downvote, komentar
|   - Sidebar: kategori populer AI, widget pengumuman
|=============================================================================
--}}

{{-- Mewarisi/meng-extend layout induk --}}
@extends('layouts.app')


{{-- ============================================================
     SECTION TITLE — Mengisi slot @yield('title') di layout induk
     ============================================================ --}}
@section('title', 'Feed Utama — AI-Connect')


{{-- ============================================================
     SECTION STYLES — CSS khusus halaman feed ini
     ============================================================ --}}
@section('styles')
<style>
    /* ===== HERO BANNER ===== */
    .feed-hero {
        background: linear-gradient(135deg, #0f0f1a 0%, #1a1a2e 50%, #16213e 100%);
        padding: 2.5rem 0 2rem;
        border-bottom: 1px solid #2e2e45;
        position: relative;
        overflow: hidden;
    }

    .feed-hero::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 300px; height: 300px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(6, 182, 212, 0.12) 0%, transparent 70%);
        pointer-events: none;
    }

    .feed-hero-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #e2e8f0;
        margin-bottom: 0.25rem;
    }

    .feed-hero-subtitle {
        color: #64748b;
        font-size: 0.9rem;
        margin-bottom: 0;
    }

    /* Tab kategori di bawah hero */
    .feed-tabs .nav-link {
        color: #64748b;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        transition: all 0.2s;
        border: none;
        background: none;
    }
    .feed-tabs .nav-link:hover,
    .feed-tabs .nav-link.active {
        color: #6366f1;
        background: rgba(99, 102, 241, 0.1);
    }

    /* ===== KARTU POSTINGAN ===== */
    .post-card {
        background: #ffffff;
        border: 1px solid #e8eaf0;
        border-radius: 14px;
        padding: 1.3rem 1.5rem;
        margin-bottom: 1rem;
        transition: box-shadow 0.2s, transform 0.2s;
    }

    .post-card:hover {
        box-shadow: 0 6px 24px rgba(99, 102, 241, 0.1);
        transform: translateY(-2px);
    }

    /* Header kartu: avatar + info user */
    .post-author-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid #e8eaf0;
        object-fit: cover;
        flex-shrink: 0;
    }

    .post-author-name {
        font-weight: 700;
        font-size: 0.9rem;
        color: #1e293b;
        text-decoration: none;
        transition: color 0.2s;
    }
    .post-author-name:hover { color: #6366f1; }

    .post-meta-time {
        font-size: 0.78rem;
        color: #94a3b8;
    }

    /* Judul dan isi postingan */
    .post-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.4rem;
        line-height: 1.4;
        text-decoration: none;
        display: block;
    }
    .post-title:hover { color: #6366f1; }

    .post-body {
        font-size: 0.875rem;
        color: #475569;
        line-height: 1.65;
        display: -webkit-box;
        -webkit-line-clamp: 3;   /* Batasi preview teks 3 baris */
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 0.9rem;
    }

    /* Tombol Source Code URL */
    .btn-source-code {
        font-size: 0.78rem;
        font-weight: 600;
        color: #06b6d4;
        border: 1px solid rgba(6, 182, 212, 0.35);
        border-radius: 20px;
        padding: 3px 12px;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .btn-source-code:hover {
        background: rgba(6, 182, 212, 0.1);
        color: #06b6d4;
    }

    /* Action bar bawah kartu: vote + komentar + share */
    .post-action-bar {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding-top: 0.85rem;
        border-top: 1px solid #f1f5f9;
        flex-wrap: wrap;
    }

    /* Komponen Vote (Upvote / Downvote ala Reddit) */
    .vote-group {
        display: inline-flex;
        align-items: center;
        background: #f8f9ff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        overflow: hidden;
    }

    .btn-vote {
        background: none;
        border: none;
        padding: 5px 10px;
        font-size: 0.82rem;
        cursor: pointer;
        transition: all 0.15s;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 3px;
    }

    .btn-vote:hover { background: rgba(99, 102, 241, 0.1); }

    /* State aktif upvote (klik pertama) */
    .btn-vote.upvoted {
        color: #6366f1;
        font-weight: 700;
    }
    /* State aktif downvote */
    .btn-vote.downvoted { color: #ef4444; }

    .vote-count {
        font-size: 0.82rem;
        font-weight: 700;
        color: #1e293b;
        padding: 0 4px;
        min-width: 28px;
        text-align: center;
    }

    .vote-divider {
        width: 1px;
        height: 22px;
        background: #e2e8f0;
    }

    /* Tombol Komentar */
    .btn-comment {
        font-size: 0.82rem;
        color: #64748b;
        background: #f8f9ff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 5px 12px;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
    }
    .btn-comment:hover {
        background: rgba(99, 102, 241, 0.08);
        border-color: #6366f1;
        color: #6366f1;
    }

    /* Tombol Share */
    .btn-share {
        font-size: 0.82rem;
        color: #94a3b8;
        background: none;
        border: none;
        padding: 5px 8px;
        cursor: pointer;
        transition: color 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .btn-share:hover { color: #6366f1; }

    /* ===== SIDEBAR ===== */
    .sidebar-card {
        background: #ffffff;
        border: 1px solid #e8eaf0;
        border-radius: 14px;
        padding: 1.2rem 1.3rem;
        margin-bottom: 1rem;
    }

    .sidebar-card-title {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #94a3b8;
        margin-bottom: 0.9rem;
        padding-bottom: 0.6rem;
        border-bottom: 1px solid #f1f5f9;
    }

    /* Item kategori di sidebar */
    .category-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.45rem 0;
        border-bottom: 1px solid #f8faff;
        text-decoration: none;
        color: #334155;
        font-size: 0.875rem;
        transition: color 0.2s;
    }
    .category-item:hover { color: #6366f1; }
    .category-item:last-child { border-bottom: none; }

    .category-icon {
        font-size: 1.1rem;
        width: 28px;
        text-align: center;
    }

    .category-count {
        font-size: 0.75rem;
        font-weight: 600;
        background: #f1f5f9;
        color: #64748b;
        padding: 1px 8px;
        border-radius: 20px;
    }

    /* Widget Pengumuman */
    .announcement-item {
        padding: 0.7rem 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.83rem;
    }
    .announcement-item:last-child { border-bottom: none; }

    .announcement-badge {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    /* Skeleton loading (placeholder saat data belum tersedia) */
    .skeleton {
        background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 6px;
    }
    @keyframes shimmer {
        0%   { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
</style>
@endsection


{{-- ============================================================
     SECTION CONTENT — Konten utama halaman feed
     ============================================================ --}}
@section('content')

    {{-- ============================================================
         HERO SECTION FEED
         Banner tipis di bawah navbar, menampilkan sapaan & statistik
         ============================================================ --}}
    <section class="feed-hero">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col">
                    <h1 class="feed-hero-title">
                        👋 Selamat datang kembali, <span style="color: #06b6d4;">Alex!</span>
                    </h1>
                    <p class="feed-hero-subtitle">
                        Ada <strong style="color:#e2e8f0;">24 postingan baru</strong> sejak kunjungan terakhirmu ·
                        <span style="color:#06b6d4;">1,247</span> anggota aktif hari ini
                    </p>
                </div>
                <div class="col-auto d-none d-md-flex gap-4 text-center">
                    {{-- Statistik cepat --}}
                    <div>
                        <div style="font-size:1.5rem; font-weight:800; color:#e2e8f0;">8.4K</div>
                        <div style="font-size:0.72rem; color:#64748b;">Total Thread</div>
                    </div>
                    <div>
                        <div style="font-size:1.5rem; font-weight:800; color:#06b6d4;">32K</div>
                        <div style="font-size:0.72rem; color:#64748b;">Anggota</div>
                    </div>
                    <div>
                        <div style="font-size:1.5rem; font-weight:800; color:#a78bfa;">156K</div>
                        <div style="font-size:0.72rem; color:#64748b;">Komentar</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- /HERO --}}


    {{-- ============================================================
         KONTEN UTAMA: 2 Kolom — Feed + Sidebar
         ============================================================ --}}
    <div class="container-xl py-4">
        <div class="row g-4">


            {{-- ========================================================
                 KOLOM KIRI/TENGAH: FEED POSTINGAN
                 ======================================================== --}}
            <div class="col-lg-8">

                {{-- Tab Filter Feed --}}
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <ul class="nav feed-tabs p-0" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active">🔥 Terpopuler</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link">🆕 Terbaru</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link">💬 Terdiskusi</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link">⭐ Tersimpan</button>
                        </li>
                    </ul>
                    <span style="font-size:0.8rem; color:#94a3b8;">24 postingan baru</span>
                </div>


                {{-- ====================================================
                     KARTU POSTINGAN #1 — Machine Learning
                     ==================================================== --}}
                <article class="post-card">
                    {{-- Header: Avatar + Nama User + Kategori + Waktu --}}
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <img
                            src="https://ui-avatars.com/api/?name=Rina+Setiawan&background=6366f1&color=fff&size=64"
                            alt="Avatar Rina Setiawan"
                            class="post-author-avatar"
                        >
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <a href="#" class="post-author-name">Rina Setiawan</a>
                                <span class="badge-category">Machine Learning</span>
                                <span class="post-meta-time">
                                    <i class="bi bi-clock me-1"></i>2 jam lalu
                                </span>
                            </div>
                        </div>
                        {{-- Tombol simpan/bookmark --}}
                        <button class="btn-share" title="Simpan postingan">
                            <i class="bi bi-bookmark"></i>
                        </button>
                    </div>

                    {{-- Judul Postingan --}}
                    <a href="{{ route('posts.show', 1) }}" class="post-title">
                        Implementasi Algoritma Gradient Boosting dari Nol — Panduan Lengkap untuk Pemula Absolut
                    </a>

                    {{-- Isi Postingan (Preview) --}}
                    <p class="post-body">
                        Halo komunitas! Kali ini saya ingin berbagi pengalaman saya mengimplementasikan
                        algoritma Gradient Boosting (XGBoost-style) tanpa menggunakan library eksternal,
                        murni menggunakan NumPy. Perjalanan ini mengajarkan saya banyak hal tentang
                        decision tree, loss function, dan konsep boosting secara mendalam...
                    </p>

                    {{-- Tombol Link Source Code --}}
                    <div class="mb-3">
                        <a href="https://github.com/rina-setiawan/gradient-boosting-scratch" target="_blank" rel="noopener" class="btn-source-code">
                            <i class="bi bi-github"></i> Lihat Source Code
                        </a>
                    </div>

                    {{-- Action Bar: Vote + Komentar + Share --}}
                    <div class="post-action-bar">

                        {{-- Grup Upvote / Downvote --}}
                        <div class="vote-group">
                            <button
                                class="btn-vote"
                                onclick="handleVote(this, 'up')"
                                aria-label="Upvote postingan"
                                title="Upvote"
                            >
                                <i class="bi bi-arrow-up-circle-fill"></i>
                            </button>
                            <span class="vote-count" id="vote-count-1">248</span>
                            <div class="vote-divider"></div>
                            <button
                                class="btn-vote"
                                onclick="handleVote(this, 'down')"
                                aria-label="Downvote postingan"
                                title="Downvote"
                            >
                                <i class="bi bi-arrow-down-circle-fill"></i>
                            </button>
                        </div>

                        {{-- Tombol Komentar --}}
                        <a href="{{ route('posts.show', 1) }}" class="btn-comment">
                            <i class="bi bi-chat-dots"></i> 42 Komentar
                        </a>

                        {{-- Tombol Share --}}
                        <button class="btn-share ms-auto" title="Bagikan">
                            <i class="bi bi-share"></i> Bagikan
                        </button>

                    </div>
                </article>
                {{-- /KARTU POSTINGAN #1 --}}


                {{-- ====================================================
                     KARTU POSTINGAN #2 — NLP / LLM
                     ==================================================== --}}
                <article class="post-card">
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <img
                            src="https://ui-avatars.com/api/?name=Dimas+Pratama&background=06b6d4&color=fff&size=64"
                            alt="Avatar Dimas Pratama"
                            class="post-author-avatar"
                        >
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <a href="#" class="post-author-name">Dimas Pratama</a>
                                <span class="badge-category">NLP</span>
                                <span class="badge-category">LLM</span>
                                <span class="post-meta-time">
                                    <i class="bi bi-clock me-1"></i>5 jam lalu
                                </span>
                            </div>
                        </div>
                        <button class="btn-share" title="Simpan postingan">
                            <i class="bi bi-bookmark"></i>
                        </button>
                    </div>

                    <a href="{{ route('posts.show', 2) }}" class="post-title">
                        Fine-tuning GPT-2 untuk Bahasa Indonesia: Dataset, Tokenisasi, dan Hasil Evaluasi BLEU Score
                    </a>

                    <p class="post-body">
                        Proyek ini dimulai dari rasa penasaran: seberapa baik model LLM barat mampu
                        di-fine-tune dengan korpus Bahasa Indonesia? Saya menggunakan dataset CC100-ID
                        sebesar 1.2GB, melakukan custom tokenizer dengan SentencePiece, dan melatih ulang
                        top-4 layer dari GPT-2 medium selama 3 epoch di Google Colab T4...
                    </p>

                    <div class="mb-3">
                        <a href="https://huggingface.co/dimas-pratama/gpt2-bahasa-indonesia" target="_blank" rel="noopener" class="btn-source-code">
                            <i class="bi bi-robot"></i> Lihat di HuggingFace
                        </a>
                        <a href="https://colab.research.google.com" target="_blank" rel="noopener" class="btn-source-code ms-2">
                            <i class="bi bi-file-earmark-code"></i> Open in Colab
                        </a>
                    </div>

                    <div class="post-action-bar">
                        <div class="vote-group">
                            <button class="btn-vote" onclick="handleVote(this, 'up')" aria-label="Upvote">
                                <i class="bi bi-arrow-up-circle-fill"></i>
                            </button>
                            <span class="vote-count" id="vote-count-2">183</span>
                            <div class="vote-divider"></div>
                            <button class="btn-vote" onclick="handleVote(this, 'down')" aria-label="Downvote">
                                <i class="bi bi-arrow-down-circle-fill"></i>
                            </button>
                        </div>
                        <a href="{{ route('posts.show', 2) }}" class="btn-comment">
                            <i class="bi bi-chat-dots"></i> 29 Komentar
                        </a>
                        <button class="btn-share ms-auto" title="Bagikan">
                            <i class="bi bi-share"></i> Bagikan
                        </button>
                    </div>
                </article>
                {{-- /KARTU POSTINGAN #2 --}}


                {{-- ====================================================
                     KARTU POSTINGAN #3 — Computer Vision
                     ==================================================== --}}
                <article class="post-card">
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <img
                            src="https://ui-avatars.com/api/?name=Nadia+Kusuma&background=a78bfa&color=fff&size=64"
                            alt="Avatar Nadia Kusuma"
                            class="post-author-avatar"
                        >
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <a href="#" class="post-author-name">Nadia Kusuma</a>
                                <span class="badge-category">Computer Vision</span>
                                <span class="post-meta-time">
                                    <i class="bi bi-clock me-1"></i>1 hari lalu
                                </span>
                            </div>
                        </div>
                        <button class="btn-share" title="Simpan postingan">
                            <i class="bi bi-bookmark-fill" style="color: #6366f1;"></i>
                        </button>
                    </div>

                    <a href="{{ route('posts.show', 3) }}" class="post-title">: Antarmuka Tanpa Sentuh untuk Presentasi
                    </a>

                    <p class="post-body">
                        Bayangkan mengontrol slide presentasimu hanya dengan gerakan tangan — tanpa remote,
                        tanpa keyboard. Itulah yang saya bangun minggu ini. Dengan MediaPipe Hands dan OpenCV,
                        saya berhasil mendeteksi 5 gestur unik (next, prev, zoom in, zoom out, stop) dengan
                        akurasi 94.3% pada kondisi pencahayaan normal...
                    </p>

                    {{-- Preview gambar postingan --}}
                    <div class="mb-3 rounded-3 overflow-hidden" style="background:#f1f5f9; height:180px; display:flex; align-items:center; justify-content:center;">
                        <div class="text-center text-muted">
                            <i class="bi bi-image fs-1" style="color:#cbd5e1;"></i>
                            <p class="mb-0 mt-2" style="font-size:0.8rem; color:#94a3b8;">Demo GIF · 2.4MB</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <a href="https://github.com/nadia-kusuma/gesture-control" target="_blank" rel="noopener" class="btn-source-code">
                            <i class="bi bi-github"></i> Source Code
                        </a>
                        <a href="https://youtu.be/demo-link" target="_blank" rel="noopener" class="btn-source-code ms-2">
                            <i class="bi bi-play-circle"></i> Tonton Demo
                        </a>
                    </div>

                    <div class="post-action-bar">
                        <div class="vote-group">
                            <button class="btn-vote" onclick="handleVote(this, 'up')" aria-label="Upvote">
                                <i class="bi bi-arrow-up-circle-fill"></i>
                            </button>
                            <span class="vote-count" id="vote-count-3">512</span>
                            <div class="vote-divider"></div>
                            <button class="btn-vote" onclick="handleVote(this, 'down')" aria-label="Downvote">
                                <i class="bi bi-arrow-down-circle-fill"></i>
                            </button>
                        </div>
                        <a href="{{ route('posts.show', 3) }}" class="btn-comment">
                        <button class="btn-share ms-auto">
                            <i class="bi bi-share"></i> Bagikan
                        </button>
                    </div>
                </article>
                {{-- /KARTU POSTINGAN #3 --}}


                {{-- ====================================================
                     KARTU POSTINGAN #4 — Reinforcement Learning
                     ==================================================== --}}
                <article class="post-card">
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <img
                            src="https://ui-avatars.com/api/?name=Budi+Hartono&background=f59e0b&color=fff&size=64"
                            alt="Avatar Budi Hartono"
                            class="post-author-avatar"
                        >
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <a href="#" class="post-author-name">Budi Hartono</a>
                                <span class="badge-category">Reinforcement Learning</span>
                                <span class="post-meta-time">
                                    <i class="bi bi-clock me-1"></i>2 hari lalu
                                </span>
                            </div>
                        </div>
                        <button class="btn-share" title="Simpan postingan">
                            <i class="bi bi-bookmark"></i>
                        </button>
                    </div>

                    <a href="{{ route('posts.show', 4) }}" class="post-title"> (Deep Q-Network): Dari 0 Hingga Skor 500+
                    </a>

                    <p class="post-body">
                        Proyek klasik tapi selalu menarik! Saya melatih agen DQN untuk bermain Snake menggunakan
                        PyTorch dengan epsilon-greedy exploration. Setelah 1000 episode, agen berhasil
                        mencapai skor rata-rata 287 dan skor tertinggi 541. Artikel ini membahas arsitektur
                        jaringan, replay buffer, dan trik training yang saya gunakan...
                    </p>

                    <div class="mb-3">
                        <a href="https://github.com/budi-hartono/dqn-snake" target="_blank" rel="noopener" class="btn-source-code">
                            <i class="bi bi-github"></i> Source Code
                        </a>
                    </div>

                    <div class="post-action-bar">
                        <div class="vote-group">
                            <button class="btn-vote" onclick="handleVote(this, 'up')" aria-label="Upvote">
                                <i class="bi bi-arrow-up-circle-fill"></i>
                            </button>
                            <span class="vote-count" id="vote-count-4">319</span>
                            <div class="vote-divider"></div>
                            <button class="btn-vote" onclick="handleVote(this, 'down')" aria-label="Downvote">
                                <i class="bi bi-arrow-down-circle-fill"></i>
                            </button>
                        </div>
                        <a href="{{ route('posts.show', 4) }}" class="btn-comment">
                            <i class="bi bi-chat-dots"></i> 55 Komentar
                        </a>
                        <button class="btn-share ms-auto">
                            <i class="bi bi-share"></i> Bagikan
                        </button>
                    </div>
                </article>
                {{-- /KARTU POSTINGAN #4 --}}


                {{-- Tombol Load More --}}
                <div class="text-center py-3">
                    <button class="btn" style="
                        background: white;
                        border: 2px solid #6366f1;
                        color: #6366f1;
                        border-radius: 20px;
                        font-weight: 600;
                        padding: 0.5rem 2rem;
                        transition: all 0.2s;
                    " onmouseover="this.style.background='#6366f1'; this.style.color='white';"
                       onmouseout="this.style.background='white'; this.style.color='#6366f1';">
                        <i class="bi bi-arrow-clockwise me-2"></i> Muat Lebih Banyak
                    </button>
                </div>

            </div>
            {{-- /KOLOM FEED --}}


            {{-- ========================================================
                 KOLOM KANAN: SIDEBAR
                 ======================================================== --}}
            <aside class="col-lg-4">

                {{-- Widget 1: Kategori AI Populer --}}
                <div class="sidebar-card">
                    <h2 class="sidebar-card-title">
                        <i class="bi bi-grid-3x3-gap me-2" style="color:#6366f1;"></i>Kategori AI Populer
                    </h2>

                    <a href="#" class="category-item">
                        <span>
                            <span class="category-icon">🤖</span>
                            Machine Learning
                        </span>
                        <span class="category-count">2.4K</span>
                    </a>

                    <a href="#" class="category-item">
                        <span>
                            <span class="category-icon">💬</span>
                            NLP & LLM
                        </span>
                        <span class="category-count">1.8K</span>
                    </a>

                    <a href="#" class="category-item">
                        <span>
                            <span class="category-icon">👁️</span>
                            Computer Vision
                        </span>
                        <span class="category-count">1.2K</span>
                    </a>

                    <a href="#" class="category-item">
                        <span>
                            <span class="category-icon">🎮</span>
                            Reinforcement Learning
                        </span>
                        <span class="category-count">876</span>
                    </a>

                    <a href="#" class="category-item">
                        <span>
                            <span class="category-icon">🧬</span>
                            Generative AI
                        </span>
                        <span class="category-count">743</span>
                    </a>

                    <a href="#" class="category-item">
                        <span>
                            <span class="category-icon">📊</span>
                            Data Science
                        </span>
                        <span class="category-count">652</span>
                    </a>

                    <a href="#" class="category-item">
                        <span>
                            <span class="category-icon">🔗</span>
                            MLOps & Deploy
                        </span>
                        <span class="category-count">389</span>
                    </a>

                    <a href="#" class="category-item">
                        <span>
                            <span class="category-icon">🤝</span>
                            Etika AI
                        </span>
                        <span class="category-count">214</span>
                    </a>

                    <div class="mt-2 pt-1">
                        <a href="#" style="font-size:0.82rem; color:#6366f1; font-weight:600; text-decoration:none;">
                            Lihat semua kategori →
                        </a>
                    </div>
                </div>

                {{-- Widget 2: Pengumuman Komunitas --}}
                <div class="sidebar-card">
                    <h2 class="sidebar-card-title">
                        <i class="bi bi-megaphone me-2" style="color:#f59e0b;"></i>Pengumuman Komunitas
                    </h2>

                    <div class="announcement-item">
                        <span class="badge announcement-badge bg-warning text-dark mb-1">📌 Pinned</span>
                        <p class="mb-0 fw-semibold" style="font-size:0.85rem; color:#1e293b;">
                            Hackathon AI-Connect Vol.3 Dibuka!
                        </p>
                        <p class="mb-0 mt-1" style="font-size:0.78rem; color:#64748b;">
                            Daftar sebelum 30 Juni — hadiah total Rp 15 Juta
                        </p>
                    </div>

                    <div class="announcement-item">
                        <span class="badge announcement-badge mb-1" style="background:rgba(99,102,241,0.15); color:#6366f1;">🎓 Workshop</span>
                        <p class="mb-0 fw-semibold" style="font-size:0.85rem; color:#1e293b;">
                            Webinar: Intro to RAG Architecture
                        </p>
                        <p class="mb-0 mt-1" style="font-size:0.78rem; color:#64748b;">
                            Sabtu, 22 Juni 2025 · 10.00 WIB · Gratis
                        </p>
                    </div>

                    <div class="announcement-item">
                        <span class="badge announcement-badge bg-success mb-1">✅ Update</span>
                        <p class="mb-0 fw-semibold" style="font-size:0.85rem; color:#1e293b;">
                            Fitur Code Preview sudah live!
                        </p>
                        <p class="mb-0 mt-1" style="font-size:0.78rem; color:#64748b;">
                            Kini postingan mendukung syntax highlighting
                        </p>
                    </div>
                </div>

                {{-- Widget 3: Kontributor Minggu Ini --}}
                <div class="sidebar-card">
                    <h2 class="sidebar-card-title">
                        <i class="bi bi-trophy me-2" style="color:#f59e0b;"></i>Top Kontributor Minggu Ini
                    </h2>

                    @php
                        // Data dummy kontributor (nanti diganti dari database)
                        $contributors = [
                            ['name' => 'Nadia Kusuma',   'score' => '1.2K poin', 'bg' => 'a78bfa'],
                            ['name' => 'Budi Hartono',   'score' => '987 poin',  'bg' => 'f59e0b'],
                            ['name' => 'Rina Setiawan',  'score' => '843 poin',  'bg' => '6366f1'],
                            ['name' => 'Dimas Pratama',  'score' => '721 poin',  'bg' => '06b6d4'],
                            ['name' => 'Sari Wulandari', 'score' => '614 poin',  'bg' => 'ef4444'],
                        ];
                        $medals = ['🥇', '🥈', '🥉', '4.', '5.'];
                    @endphp

                    @foreach($contributors as $index => $contributor)
                    <div class="d-flex align-items-center gap-2 py-2
                                {{ !$loop->last ? 'border-bottom' : '' }}"
                         style="border-color:#f1f5f9;">
                        <span style="font-size:1rem; width:24px; text-align:center;">
                            {{ $medals[$index] }}
                        </span>
                        <img
                            src="https://ui-avatars.com/api/?name={{ urlencode($contributor['name']) }}&background={{ $contributor['bg'] }}&color=fff&size=32"
                            alt="{{ $contributor['name'] }}"
                            style="width:28px; height:28px; border-radius:50%;"
                        >
                        <span style="font-size:0.82rem; font-weight:600; color:#1e293b; flex:1;">
                            {{ $contributor['name'] }}
                        </span>
                        <span style="font-size:0.75rem; color:#6366f1; font-weight:700;">
                            {{ $contributor['score'] }}
                        </span>
                    </div>
                    @endforeach
                </div>

                {{-- Widget 4: Hint Akses Admin (hanya teks petunjuk, tidak ada link nyata) --}}
                <div class="sidebar-card" style="background: #fffbeb; border-color: #fde68a;">
                    <p class="mb-0" style="font-size:0.78rem; color:#92400e;">
                        <i class="bi bi-keyboard me-1"></i>
                        <strong>Shortcut:</strong> Tekan <kbd>Ctrl</kbd> + <kbd>Alt</kbd> + <kbd>A</kbd>
                        untuk mode khusus.
                    </p>
                </div>

            </aside>
            {{-- /SIDEBAR --}}

        </div>
    </div>
    {{-- /KONTEN UTAMA --}}

@endsection


{{-- ============================================================
     SECTION SCRIPTS — JS khusus halaman feed
     ============================================================ --}}
@section('scripts')
<script>
    /**
     * HANDLE VOTE — Fungsi Upvote/Downvote ala Reddit
     * ------------------------------------------------
     * Menangani logika toggle untuk tombol vote pada setiap kartu postingan.
     *
     * Cara kerja:
     * 1. Cari kontainer .vote-group dari tombol yang diklik.
     * 2. Ambil elemen tampilan angka vote (.vote-count).
     * 3. Jika tombol yang diklik sudah aktif → batalkan vote (toggle off).
     * 4. Jika belum aktif → aktifkan dan nonaktifkan lawan.
     * 5. Update angka vote sesuai perubahan.
     *
     * @param {HTMLElement} button - Tombol yang diklik (upvote/downvote).
     * @param {string}      type   - Jenis vote: 'up' atau 'down'.
     */
    function handleVote(button, type) {
        // Temukan kontainer vote yang mengandung kedua tombol
        const voteGroup   = button.closest('.vote-group');
        const countEl     = voteGroup.querySelector('.vote-count');
        let   currentVote = parseInt(countEl.textContent, 10);

        // Cek apakah tombol ini sudah dalam kondisi aktif (sudah divote)
        const isAlreadyActive = button.classList.contains(type === 'up' ? 'upvoted' : 'downvoted');

        // Reset semua state aktif terlebih dahulu
        voteGroup.querySelectorAll('.btn-vote').forEach(btn => {
            btn.classList.remove('upvoted', 'downvoted');
        });

        if (isAlreadyActive) {
            // Jika tombol yang sama diklik lagi → batalkan vote
            countEl.textContent = type === 'up' ? currentVote - 1 : currentVote + 1;
        } else {
            // Aktifkan tombol yang baru diklik
            if (type === 'up') {
                button.classList.add('upvoted');
                countEl.textContent = currentVote + 1;
            } else {
                button.classList.add('downvoted');
                countEl.textContent = currentVote - 1;
            }
        }

        // Catatan untuk pengembangan lanjut:
        // Di sini kamu bisa menambahkan AJAX/fetch() ke API Laravel untuk
        // menyimpan data vote ke database, misalnya:
        //
        // fetch(`/api/posts/${postId}/vote`, {
        //     method: 'POST',
        //     headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        //     body: JSON.stringify({ type: type })
        // });
    }

    /**
     * Animasi sederhana saat kartu postingan masuk ke viewport
     * Menggunakan IntersectionObserver API (native, tanpa library).
     */
    const postCards = document.querySelectorAll('.post-card');
    const observer  = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity   = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 }); // Trigger saat 10% kartu terlihat

    postCards.forEach(card => {
        // Set state awal (tersembunyi & sedikit ke bawah)
        card.style.opacity   = '0';
        card.style.transform = 'translateY(16px)';
        card.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
        observer.observe(card);
    });
</script>
@endsection
