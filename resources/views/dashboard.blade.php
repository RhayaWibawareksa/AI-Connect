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
                        👋 Selamat datang kembali, <span style="color: #06b6d4;">{{ auth()->user()?->name ?? 'AI Explorer' }}</span>
                    </h1>
                    <p class="feed-hero-subtitle">
                        Ada <strong style="color:#e2e8f0;">{{ $stats['new_posts'] }} postingan baru</strong> sejak 24 jam terakhir ·
                        <span style="color:#06b6d4;">{{ $stats['active_members'] }}</span> anggota aktif hari ini
                    </p>
                </div>
                <div class="col-auto d-none d-md-flex gap-4 text-center">
                    <div>
                        <div style="font-size:1.5rem; font-weight:800; color:#e2e8f0;">{{ $stats['total_threads'] }}</div>
                        <div style="font-size:0.72rem; color:#64748b;">Total Thread</div>
                    </div>
                    <div>
                        <div style="font-size:1.5rem; font-weight:800; color:#06b6d4;">{{ $stats['total_members'] }}</div>
                        <div style="font-size:0.72rem; color:#64748b;">Anggota</div>
                    </div>
                    <div>
                        <div style="font-size:1.5rem; font-weight:800; color:#a78bfa;">{{ $stats['total_comments'] }}</div>
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
                @php
                    $baseQuery = request()->except(['page', 'filter']);
                @endphp
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <ul class="nav feed-tabs p-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{ $filter === 'popular' ? 'active' : '' }}" href="{{ route('dashboard', array_merge($baseQuery, ['filter' => 'popular'])) }}">🔥 Terpopuler</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $filter === 'new' ? 'active' : '' }}" href="{{ route('dashboard', array_merge($baseQuery, ['filter' => 'new'])) }}">🆕 Terbaru</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $filter === 'discussed' ? 'active' : '' }}" href="{{ route('dashboard', array_merge($baseQuery, ['filter' => 'discussed'])) }}">💬 Terdiskusi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $filter === 'saved' ? 'active' : '' }}" href="{{ route('dashboard', array_merge($baseQuery, ['filter' => 'saved'])) }}">⭐ Tersimpan</a>
                        </li>
                    </ul>
                    <span style="font-size:0.8rem; color:#94a3b8;">{{ $stats['new_posts'] }} postingan baru</span>
                </div>

                <div id="feed-container">
                    @include('posts.partials.feed-loop', ['posts' => $posts])
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $posts->appends(request()->query())->links('pagination::bootstrap-5') }}
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

                    @php
                        $categoryIcons = ['🤖','💬','👁️','🎮','🧬','📊','🔗','🤝'];
                    @endphp
                    @foreach($sidebarCategories as $index => $category)
                        <a href="{{ route('dashboard', array_merge($baseQuery, ['category' => $category->slug])) }}" class="category-item">
                            <span>
                                <span class="category-icon">{{ $categoryIcons[$index % count($categoryIcons)] }}</span>
                                {{ $category->name }}
                            </span>
                            <span class="category-count">{{ $category->posts_count }}</span>
                        </a>
                    @endforeach

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
                        $medals = ['🥇', '🥈', '🥉', '4️⃣', '5️⃣'];
                    @endphp

                    @foreach($contributors as $index => $contributor)
                    <div class="d-flex align-items-center gap-2 py-2
                                {{ !$loop->last ? 'border-bottom' : '' }}"
                         style="border-color:#f1f5f9;">
                        <span style="font-size:1rem; width:24px; text-align:center;">
                            {{ $medals[$index] ?? '⭐' }}
                        </span>
                        <img
                            src="https://ui-avatars.com/api/?name={{ urlencode($contributor->name) }}&background={{ $contributor->avatar_bg }}&color=fff&size=32"
                            alt="{{ $contributor->name }}"
                            style="width:28px; height:28px; border-radius:50%;"
                        >
                        <span style="font-size:0.82rem; font-weight:600; color:#1e293b; flex:1;">
                            {{ $contributor->name }}
                        </span>
                        <span style="font-size:0.75rem; color:#6366f1; font-weight:700;">
                            {{ $contributor->score }} poin
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
