{{--
|=============================================================================
| LAYOUT INDUK: resources/views/layouts/app.blade.php
|=============================================================================
| Template dasar (parent layout) yang diwarisi oleh semua halaman.
| Berisi:
|   - Struktur HTML5 dasar (<head>, <body>)
|   - CDN Bootstrap 5 (CSS & JS)
|   - Definisi @yield untuk slot konten dinamis
|   - Script Event Listener rahasia (Ctrl + Alt + A → /admin-secret)
|=============================================================================
--}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Judul halaman dinamis; child page bisa menggantinya via @section('title') --}}
    <title>@yield('title', 'AI-Connect — Forum Komunitas AI')</title>

    {{-- ================================================================
         CDN Bootstrap 5.3 — CSS
         Menggunakan CDN agar tidak perlu instalasi npm/webpack.
         ================================================================ --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"
    >

    {{-- Bootstrap Icons — ikon vektor gratis dari ekosistem Bootstrap --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    {{-- ================================================================
         Custom CSS Global
         Variabel CSS (custom properties) didefinisikan di :root agar
         mudah diubah-ubah untuk theming seluruh aplikasi.
         ================================================================ --}}
    <style>
        /* --- Palet Warna & Token Desain Utama --- */
        :root {
            --ai-primary:     #6366f1; /* Indigo — warna brand utama */
            --ai-primary-dk:  #4f46e5; /* Indigo gelap untuk hover */
            --ai-accent:      #06b6d4; /* Cyan — aksen highlight */
            --ai-dark-bg:     #0f0f1a; /* Latar belakang gelap (navbar/admin) */
            --ai-surface:     #1e1e2e; /* Kartu/surface di atas bg gelap */
            --ai-border:      #2e2e45; /* Warna border halus */
            --ai-text-main:   #e2e8f0; /* Teks utama pada bg gelap */
            --ai-text-muted:  #94a3b8; /* Teks sekunder / muted */
            --ai-feed-bg:     #f8f9ff; /* Latar feed publik (sedikit ungu-susu) */
        }

        /* --- Reset & Base --- */
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: var(--ai-feed-bg);
            color: #1e293b;
            min-height: 100vh;
        }

        /* --- Navbar Global --- */
        .ai-navbar {
            background: var(--ai-dark-bg);
            border-bottom: 1px solid var(--ai-border);
            padding: 0.6rem 0;
            position: sticky;
            top: 0;
            z-index: 1030; /* Bootstrap z-index navbar */
            overflow: visible;
        }

        .dropdown,
        .ai-navbar .dropdown {
            overflow: visible;
        }

        .ai-navbar .dropdown-menu {
            z-index: 2050 !important;
        }

        .ai-logo {
            font-size: 1.3rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .ai-logo .logo-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--ai-accent);
            display: inline-block;
            animation: pulse-dot 2s infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.5; transform: scale(1.3); }
        }

        .ai-search-input {
            background: var(--ai-surface);
            border: 1px solid var(--ai-border);
            color: var(--ai-text-main);
            border-radius: 20px;
            padding: 0.4rem 1rem;
            font-size: 0.875rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .ai-search-input::placeholder { color: var(--ai-text-muted); }
        .ai-search-input:focus {
            outline: none;
            border-color: var(--ai-primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25);
            background: var(--ai-surface);
            color: var(--ai-text-main);
        }

        .btn-create-post {
            background: var(--ai-primary);
            color: #fff;
            border: none;
            border-radius: 20px;
            padding: 0.4rem 1.1rem;
            font-size: 0.875rem;
            font-weight: 600;
            transition: background 0.2s, transform 0.1s;
        }
        .btn-create-post:hover {
            background: var(--ai-primary-dk);
            color: #fff;
            transform: translateY(-1px);
        }

        .ai-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            border: 2px solid var(--ai-primary);
            object-fit: cover;
            cursor: pointer;
        }

        /* --- Utilitas --- */
        .text-accent { color: var(--ai-accent); }
        .badge-category {
            background: rgba(99, 102, 241, 0.15);
            color: var(--ai-primary);
            border: 1px solid rgba(99, 102, 241, 0.3);
            font-size: 0.72rem;
            font-weight: 600;
            padding: 2px 9px;
            border-radius: 20px;
        }

        /* --- Pagination UI polish --- */
        .pagination {
            display: inline-flex !important;
            flex-wrap: wrap !important;
            justify-content: center !important;
            align-items: center !important;
            padding-left: 0 !important;
            margin: 0.75rem 0 !important;
            border-radius: 0.75rem !important;
            font-size: 0.82rem !important;
        }

        .pagination .page-item {
            margin: 0 0.08rem !important;
        }

        .pagination .page-link {
            min-width: auto !important;
            padding: 0.42rem 0.65rem !important;
            font-size: 0.82rem !important;
            line-height: 1 !important;
            border-radius: 0.65rem !important;
            color: #334155 !important;
            background-color: #fff !important;
            border: 1px solid rgba(148, 163, 184, 0.25) !important;
            transition: background-color 0.2s ease, border-color 0.2s ease !important;
            white-space: nowrap !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-height: 1.8rem !important;
            box-shadow: none !important;
            letter-spacing: 0.01em !important;
        }

        .pagination .page-item:first-child .page-link,
        .pagination .page-item:last-child .page-link {
            padding: 0.36rem 0.72rem !important;
        }

        .pagination .page-link i,
        .pagination .page-link svg,
        .pagination .page-link .bi {
            width: 0.95rem !important;
            height: 0.95rem !important;
            font-size: 0.85rem !important;
            line-height: 1 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            vertical-align: middle !important;
        }

        .pagination .page-link[aria-hidden="true"],
        .pagination .page-link[rel="next"],
        .pagination .page-link[rel="prev"] {
            font-size: 0.92rem !important;
            padding: 0.36rem 0.7rem !important;
        }

        .pagination .page-link:hover {
            background-color: #f8fafc !important;
            border-color: rgba(99, 102, 241, 0.35) !important;
        }

        .pagination .page-item.active .page-link {
            background-color: var(--ai-primary) !important;
            border-color: var(--ai-primary) !important;
            color: #fff !important;
            font-weight: 600 !important;
        }

        .pagination .page-item.disabled .page-link {
            color: #94a3b8 !important;
            background-color: #f8fafc !important;
            border-color: rgba(148, 163, 184, 0.2) !important;
        }

        .pagination .page-link:focus {
            box-shadow: 0 0 0 0.16rem rgba(99, 102, 241, 0.16) !important;
        }
    </style>

    {{-- Slot untuk CSS tambahan dari halaman child --}}
    @yield('styles')
</head>
<body>

    {{-- ================================================================
         NAVBAR UTAMA
         Komponen navigasi global yang muncul di setiap halaman.
         Menggunakan sticky-top agar selalu terlihat saat scroll.
         ================================================================ --}}
    <nav class="ai-navbar">
        <div class="container-xl">
            <div class="d-flex align-items-center gap-3">

                {{-- Logo --}}
                <a href="{{ url('/') }}" class="ai-logo me-2">
                    <span class="logo-dot"></span>
                    AI<span style="color: var(--ai-accent);">Connect</span>
                </a>

                {{-- Search Bar (menyembunyikan di layar sangat kecil) --}}
                <form id="global-search-form" action="{{ route('dashboard') }}" method="GET" class="flex-grow-1 d-none d-sm-block" style="max-width: 480px;">
                    <input
                        id="global-search-input"
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control ai-search-input w-100"
                        placeholder="&#x1F50D; Cari thread, topik, atau pengguna..."
                        aria-label="Pencarian"
                    >
                </form>

                {{-- Spacer --}}
                <div class="ms-auto d-flex align-items-center gap-3">

                    {{-- Tombol Buat Post --}}
                    <a href="{{ route('posts.create') }}" class="btn btn-create-post">
                        <i class="bi bi-plus-lg me-1"></i> Buat Post
                    </a>

                    {{-- Notifikasi langsung ke halaman --}}
                    <div class="position-relative">
                        <a id="notifications-btn" href="{{ route('notifications.index') }}" class="btn btn-link p-0 position-relative text-decoration-none" style="color: var(--ai-text-muted);">
                            <i class="bi bi-bell fs-5"></i>
                            <span id="notifications-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:0.6rem; display:none; pointer-events:none;">0</span>
                        </a>
                    </div>

                    {{-- Avatar Profil (Initials) - Tailwind-style dropdown --}}
                    <div id="profile-dropdown" class="position-relative" style="min-width:40px;">
                        <!-- Clickable initials button -->
                        <button
                            id="profile-btn"
                            class="d-inline-flex align-items-center justify-content-center rounded-circle"
                            style="width:34px;height:34px;border-radius:50%;background:var(--ai-primary);color:#fff;font-weight:700;border:none;cursor:pointer;"
                            aria-haspopup="true"
                            aria-expanded="false"
                            @auth
                                data-bs-toggle="tooltip"
                                title="{{ auth()->user()->name }}"
                            @endauth
                        >
                            @auth
                                @php
                                    $user = auth()->user();
                                    $avatar = $user->profile_photo_url ?? ($user->avatar ?? null);
                                    if (! $avatar && isset($user->profile_photo_path)) {
                                        $avatar = \Illuminate\Support\Facades\Storage::url($user->profile_photo_path);
                                    }
                                    $name = trim($user->name ?? '');
                                    $initials = collect(explode(' ', $name))->filter()->map(function($p){ return mb_substr($p,0,1); })->take(2)->join('');
                                @endphp

                                @if($avatar)
                                    <img src="{{ $avatar }}" alt="{{ $user->name }}" class="ai-avatar" />
                                @else
                                    {{ strtoupper($initials ?: mb_substr($user->email ?? 'U',0,1)) }}
                                @endif
                            @else
                                U
                            @endauth
                        </button>

                        <!-- Dropdown menu (hidden by default) -->
                        <div
                            id="profile-menu"
                            class="profile-menu d-none"
                            style="position:absolute;right:0;top:42px;z-index:2000;min-width:180px;background:#fff;color:#111;border:1px solid var(--ai-border);border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,0.08);overflow:hidden;"
                            role="menu"
                            aria-labelledby="profile-btn"
                        >
                            <a href="{{ route('dashboard') }}" class="d-block px-3 py-2" style="color:var(--ai-text-main);text-decoration:none;">Dashboard</a>
                            <div style="border-top:1px solid var(--ai-border);"></div>

                            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                @csrf
                                <button type="submit" class="d-block w-100 text-start px-3 py-2" style="background:transparent;border:none;color:#b91c1c;">Logout</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </nav>
    {{-- /NAVBAR --}}


    {{-- ================================================================
         KONTEN UTAMA
         @yield('content') adalah placeholder yang akan diisi oleh
         masing-masing halaman child yang meng-extend layout ini.
         ================================================================ --}}
    <main>
        @yield('content')
    </main>


    {{-- ================================================================
         FOOTER GLOBAL
         ================================================================ --}}
    <footer class="py-4 mt-5 text-center" style="background: var(--ai-dark-bg); color: var(--ai-text-muted); font-size: 0.8rem;">
        <div class="container">
            <p class="mb-1">
                &copy; {{ date('Y') }} <strong style="color: var(--ai-accent);">AI-Connect</strong> — Proyek Akhir Semester 2, Teknik Informatika
            </p>
            <p class="mb-0">
                Dibuat dengan <i class="bi bi-heart-fill text-danger"></i> menggunakan Laravel &amp; Bootstrap 5
            </p>
        </div>
    </footer>


    {{-- ================================================================
         CDN Bootstrap 5 — JavaScript Bundle (termasuk Popper.js)
         Letakkan JS di bawah <body> agar halaman terasa lebih cepat dimuat.
         ================================================================ --}}
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc4s9bIOgUxi8T/jzmE2GXUf7Fy6uRx8MrHpKJEPTkm"
        crossorigin="anonymous"
    ></script>


    {{-- ================================================================
         SCRIPT RAHASIA: EVENT LISTENER CTRL + ALT + A
         ================================================================
         Cara kerja:
         1. Skrip ini mendengarkan setiap tombol yang ditekan (event 'keydown')
            di seluruh halaman, di level document (jadi berlaku global).
         2. Jika ketiga kondisi terpenuhi secara bersamaan:
              - event.ctrlKey  === true  → tombol Ctrl sedang ditekan
              - event.altKey   === true  → tombol Alt sedang ditekan
              - event.key      === 'a'   → tombol huruf 'a' ditekan
            (perhatikan 'a' huruf kecil; jika Shift ditekan, event.key menjadi 'A')
         3. Maka navigator browser diarahkan (redirect) ke route admin rahasia.
         ================================================================ --}}
    <script>
        /**
         * SECRET_KEY_LISTENER
         * -------------------
         * Fungsi ini dipasang sebagai event listener global pada objek `document`.
         * Ia akan terpanggil setiap kali pengguna menekan tombol keyboard.
         *
         * @param {KeyboardEvent} event - Objek event keyboard dari browser.
         *        Properti penting:
         *          - event.ctrlKey  : boolean, true jika Ctrl sedang ditekan
         *          - event.altKey   : boolean, true jika Alt sedang ditekan
         *          - event.key      : string, nama tombol yang ditekan
         *          - event.preventDefault() : mencegah aksi default browser
         */
        document.addEventListener('keydown', function(event) {

            // Cek apakah KETIGA tombol ditekan bersamaan: Ctrl + Alt + A
            if (event.ctrlKey && event.altKey && event.key === 'a') {

                // Mencegah aksi default browser yang mungkin terpicu
                // (beberapa browser memiliki shortcut bawaan untuk Ctrl+Alt+A)
                event.preventDefault();

                // Tampilkan konfirmasi sebelum redirect (opsional, untuk UX lebih baik)
                // Kamu bisa menghapus baris ini jika ingin redirect langsung tanpa konfirmasi
                const confirmed = confirm('🔐 Akses Admin: Apakah Anda yakin ingin masuk ke panel admin?');

                if (confirmed) {
                    // Redirect browser ke halaman admin rahasia
                    // Ganti URL ini sesuai route yang kamu definisikan di routes/web.php
                    // Contoh di routes/web.php:
                    //   Route::get('/admin-secret', [AdminController::class, 'index'])->name('admin.secret');
                    window.location.href = '/admin-secret';
                }
            }

        }); // Akhir event listener

        /**
         * PETUNJUK PENGGUNAAN (untuk developer):
         * ----------------------------------------
         * 1. Tekan Ctrl + Alt + A di mana saja dalam aplikasi ini.
         * 2. Konfirmasi dialog akan muncul.
         * 3. Klik OK → browser akan diarahkan ke /admin-secret.
         * 4. Jangan bagikan kombinasi ini kepada pengguna biasa!
         *
         * Untuk menonaktifkan konfirmasi, ubah blok di atas menjadi:
         *   if (event.ctrlKey && event.altKey && event.key === 'a') {
         *       event.preventDefault();
         *       window.location.href = '/admin-secret';
         *   }
         */
    </script>

    <script>
        const isLoggedIn = @json(auth()->check());

        window.handleVote = async function (button, postId, type) {
            if (!isLoggedIn) {
                alert('Silakan masuk terlebih dahulu untuk memberi vote.');
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const response = await fetch(`/posts/${postId}/vote`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ type })
            });

            const data = await response.json();
            if (!response.ok) {
                alert(data.message || 'Gagal menyimpan vote.');
                return;
            }

            const voteGroup = button.closest('.vote-group');
            const countEl = voteGroup?.querySelector('.vote-count') || document.getElementById(`detail-vote-count-${postId}`);
            if (countEl) {
                countEl.textContent = data.votes;
            }

            voteGroup?.querySelectorAll('.btn-vote').forEach((btn) => {
                btn.classList.remove('upvoted', 'downvoted');
            });

            if (data.user_vote === 'up') {
                button.classList.add('upvoted');
            } else if (data.user_vote === 'down') {
                button.classList.add('downvoted');
            }
        };

        window.handleBookmark = async function (button, postId) {
            if (!isLoggedIn) {
                alert('Silakan masuk terlebih dahulu untuk menyimpan postingan.');
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const response = await fetch(`/posts/${postId}/bookmark`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();
            if (!response.ok) {
                alert(data.message || 'Gagal mengubah bookmark.');
                return;
            }

            const icon = button.querySelector('i');
            if (icon) {
                icon.className = data.bookmarked ? 'bi bi-bookmark-fill' : 'bi bi-bookmark';
                icon.style.color = data.bookmarked ? '#6366f1' : '';
            }
        };

        window.sharePost = async function (url) {
            try {
                if (navigator.share) {
                    await navigator.share({ title: 'AI-Connect', text: 'Lihat postingan ini', url });
                    return;
                }
                await navigator.clipboard.writeText(url);
                alert('Tautan postingan berhasil disalin.');
            } catch (error) {
                alert('Gagal membagikan tautan.');
            }
        };

        window.reportPost = async function (postId) {
            if (!isLoggedIn) {
                alert('Silakan masuk terlebih dahulu untuk melaporkan postingan.');
                return;
            }

            const reason = prompt('Alasan pelaporan (opsional). Jelaskan singkat mengapa postingan ini bermasalah:');
            if (reason === null) return; // user cancelled

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            try {
                const resp = await fetch(`/posts/${postId}/report`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ reason })
                });

                const data = await resp.json();
                if (!resp.ok) {
                    alert(data.message || 'Gagal mengirim laporan.');
                    return;
                }

                alert(data.message || 'Laporan berhasil dikirim.');
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan saat mengirim laporan.');
            }
        };

        // Notifications: fetch and render
        async function fetchNotifications() {
            if (!isLoggedIn) return;
            try {
                const resp = await fetch('/notifications', { headers: { 'Accept': 'application/json' } });
                if (!resp.ok) return;
                const data = await resp.json();
                const badge = document.getElementById('notifications-badge');
                const list = document.getElementById('notifications-list');
                const loading = document.getElementById('notifications-loading');
                const empty = document.getElementById('notifications-empty');

                console.log('Notifications payload:', data);
                loading && (loading.classList.remove('d-none'));
                list && (list.innerHTML = '');

                // update badge
                if (data.unread && data.unread > 0) {
                    badge.style.display = 'inline-block';
                    badge.textContent = data.unread;
                } else {
                    badge.style.display = 'none';
                }

                // render items
                if (!data.items || data.items.length === 0) {
                    empty && empty.classList.remove('d-none');
                } else {
                    empty && empty.classList.add('d-none');
                    data.items.forEach(function (it) {
                        const a = document.createElement('a');
                        a.href = '#';
                        a.className = 'dropdown-item notification-item';
                        a.style.color = 'var(--ai-text-main)';
                        a.dataset.notificationId = it.id;
                        a.dataset.notificationUrl = it.url || '#';
                        a.dataset.notificationMessage = it.message;
                        a.dataset.notificationTime = it.time;
                        a.innerHTML = `<div style="display:flex;justify-content:space-between;align-items:center;"><div style="max-width:78%">${it.message}</div><div style="font-size:0.8rem;color:var(--ai-text-muted);">${it.time}</div></div>`;
                        list.appendChild(a);
                    });
                }
                loading && (loading.classList.add('d-none'));
            } catch (err) {
                console.error('Fetch notifications failed', err);
            }
        }

        // Mark visible notifications as read (send ids to server)
        async function markVisibleAsRead() {
            const list = document.getElementById('notifications-list');
            if (!list) return;
            const ids = Array.from(list.querySelectorAll('[data-notification-id]')).map(el => el.dataset.notificationId);
            if (ids.length === 0) return;
            const csrf = document.querySelector('meta[name="csrf-token"]').content;
            try {
                await fetch('/notifications/mark-read', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ ids })
                });
                // hide badge
                const badge = document.getElementById('notifications-badge');
                if (badge) badge.style.display = 'none';
            } catch (err) {
                console.error('Mark read failed', err);
            }
        }

        // Wire dropdown events
        document.addEventListener('DOMContentLoaded', function () {
            fetchNotifications();
            const notifBtn = document.getElementById('notifications-btn');
            const notifMenu = document.getElementById('notifications-menu');
            const notifDropdownWrapper = document.querySelector('.dropdown');
            let notifDropdownInstance = null;

// No dropdown behavior is needed for the notification bell link.
                if (notifBtn) {
                    notifBtn.removeAttribute('data-bs-toggle');
            }

            // Live search: submit via AJAX and replace feed container
            const searchForm = document.getElementById('global-search-form');
            const searchInput = document.getElementById('global-search-input');
            if (searchForm && searchInput) {
                searchForm.addEventListener('submit', async function (e) {
                    e.preventDefault();
                    const q = searchInput.value.trim();
                    const url = new URL(searchForm.action, window.location.origin);
                    if (q) url.searchParams.set('search', q);
                    // preserve other query params? not necessary here
                    try {
                        const resp = await fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                        if (!resp.ok) {
                            window.location.href = url.toString();
                            return;
                        }
                        const html = await resp.text();
                        const container = document.getElementById('feed-container');
                        if (container) container.innerHTML = html;
                    } catch (err) {
                        console.error('Search failed', err);
                        window.location.href = url.toString();
                    }
                });
            }
        });
    </script>

        <script>
            // Profile initials dropdown: toggle + outside-click + Escape handling
            document.addEventListener('DOMContentLoaded', function () {
                const btn = document.getElementById('profile-btn');
                const menu = document.getElementById('profile-menu');
                const container = document.getElementById('profile-dropdown');

                if (!btn || !menu || !container) return;

                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    menu.classList.toggle('d-none');
                    btn.setAttribute('aria-expanded', menu.classList.contains('d-none') ? 'false' : 'true');
                });

                // Initialize Bootstrap tooltips (for elements with data-bs-toggle="tooltip")
                if (typeof bootstrap !== 'undefined') {
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    tooltipTriggerList.forEach(function (el) {
                        try { new bootstrap.Tooltip(el); } catch (e) { /* ignore */ }
                    });
                }

                // Close when clicking outside
                document.addEventListener('click', function (e) {
                    if (!menu.classList.contains('d-none') && !container.contains(e.target)) {
                        menu.classList.add('d-none');
                        btn.setAttribute('aria-expanded','false');
                    }
                });

                // Close on Escape key
                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') {
                        menu.classList.add('d-none');
                        btn.setAttribute('aria-expanded','false');
                    }
                });
            });
        </script>

    {{-- Slot untuk JavaScript tambahan dari halaman child --}}
    @yield('scripts')

</body>
</html>
