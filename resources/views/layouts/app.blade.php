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
                <div class="flex-grow-1 d-none d-sm-block" style="max-width: 480px;">
                    <input
                        type="text"
                        class="form-control ai-search-input w-100"
                        placeholder="&#x1F50D; Cari thread, topik, atau pengguna..."
                        aria-label="Pencarian"
                    >
                </div>

                {{-- Spacer --}}
                <div class="ms-auto d-flex align-items-center gap-3">

                    {{-- Tombol Buat Post --}}
                    <a href="{{ url('/posts/create') }}" class="btn btn-create-post">
                        <i class="bi bi-plus-lg me-1"></i> Buat Post
                    </a>

                    {{-- Notifikasi --}}
                    <a href="#" class="position-relative text-decoration-none" style="color: var(--ai-text-muted);">
                        <i class="bi bi-bell fs-5"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:0.6rem;">3</span>
                    </a>

                    {{-- Avatar Profil User --}}
                    <div class="dropdown">
                        <img
                            src="https://ui-avatars.com/api/?name=User+AI&background=6366f1&color=fff&size=64"
                            alt="Foto Profil"
                            class="ai-avatar dropdown-toggle"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                        <ul class="dropdown-menu dropdown-menu-end" style="background: var(--ai-surface); border-color: var(--ai-border);">
                            <li>
                                <a class="dropdown-item" href="#" style="color: var(--ai-text-main);">
                                    <i class="bi bi-person-circle me-2"></i> Profil Saya
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" style="color: var(--ai-text-main);">
                                    <i class="bi bi-gear me-2"></i> Pengaturan
                                </a>
                            </li>
                            <li><hr class="dropdown-divider" style="border-color: var(--ai-border);"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="{{ url('/logout') }}">
                                    <i class="bi bi-box-arrow-right me-2"></i> Keluar
                                </a>
                            </li>
                        </ul>
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

    {{-- Slot untuk JavaScript tambahan dari halaman child --}}
    @yield('scripts')

</body>
</html>
