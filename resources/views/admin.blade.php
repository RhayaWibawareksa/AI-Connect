{{--
|=============================================================================
| HALAMAN ADMIN: resources/views/admin.blade.php
|=============================================================================
| Panel manajemen internal yang HANYA bisa diakses via shortcut rahasia
| Ctrl + Alt + A (didefinisikan di layout induk).
|
| Halaman ini TIDAK meng-extend layout publik (app.blade.php) karena:
|   - Desain visual sengaja berbeda & lebih "tegas"
|   - Tidak ada navbar publik
|   - Memiliki sidebar admin tersendiri
|
| Komponen:
|   - Header admin dengan branding berbeda
|   - Sidebar navigasi admin
|   - Kartu ringkasan statistik (KPI)
|   - Tabel postingan masuk
|   - Tabel manajemen user
|   - Tabel laporan konten (content report)
|=============================================================================
--}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔐 Panel Admin — AI-Connect</title>

    {{-- Bootstrap 5 CSS --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"
    >

    {{-- Bootstrap Icons --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>
        /* ===================================================================
         * TOKEN DESAIN PANEL ADMIN
         * Purposefully dark, serious, dan berbeda dari feed publik.
         * Palet monokrom dengan satu aksen merah/amber untuk peringatan.
         * =================================================================== */
        :root {
            --adm-bg:        #070b14;  /* Latar belakang utama — sangat gelap */
            --adm-surface:   #0d1117;  /* Permukaan kartu/panel */
            --adm-surface2:  #161b22;  /* Permukaan sekunder (sidebar, header tabel) */
            --adm-border:    #21262d;  /* Warna garis pemisah */
            --adm-accent:    #f85149;  /* Merah — simbol akses terbatas/bahaya */
            --adm-green:     #3fb950;  /* Hijau — status aktif/aman */
            --adm-amber:     #d29922;  /* Kuning/amber — peringatan */
            --adm-blue:      #58a6ff;  /* Biru — link/aksi */
            --adm-text:      #c9d1d9;  /* Teks utama */
            --adm-text-muted:#8b949e;  /* Teks sekunder */
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            background-color: var(--adm-bg);
            color: var(--adm-text);
            font-family: 'Consolas', 'SF Mono', 'Fira Code', 'Segoe UI', monospace;
            font-size: 0.9rem;
            min-height: 100vh;
        }

        /* ===== LAYOUT UTAMA: Sidebar + Konten ===== */
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ADMIN ===== */
        .admin-sidebar {
            width: 240px;
            flex-shrink: 0;
            background: var(--adm-surface2);
            border-right: 1px solid var(--adm-border);
            padding: 0;
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
        }

        .admin-sidebar-logo {
            padding: 1.4rem 1.2rem;
            border-bottom: 1px solid var(--adm-border);
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .sidebar-logo-icon {
            width: 34px; height: 34px;
            background: var(--adm-accent);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
        }

        .sidebar-logo-text {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--adm-text);
            letter-spacing: -0.3px;
        }

        .sidebar-logo-sub {
            font-size: 0.68rem;
            color: var(--adm-accent);
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .sidebar-section-label {
            padding: 1rem 1.2rem 0.4rem;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: var(--adm-text-muted);
        }

        .sidebar-nav-link {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0.55rem 1.2rem;
            color: var(--adm-text-muted);
            text-decoration: none;
            font-size: 0.84rem;
            transition: all 0.15s;
            border-left: 3px solid transparent;
        }

        .sidebar-nav-link:hover,
        .sidebar-nav-link.active {
            color: var(--adm-text);
            background: rgba(255,255,255,0.04);
            border-left-color: var(--adm-accent);
        }

        .sidebar-nav-link.active { color: #fff; }

        .sidebar-nav-link .nav-icon { font-size: 1rem; width: 20px; text-align: center; }

        .sidebar-badge {
            margin-left: auto;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 1px 7px;
            border-radius: 10px;
        }

        /* ===== AREA KONTEN UTAMA ADMIN ===== */
        .admin-main {
            margin-left: 240px;
            flex: 1;
            min-width: 0;
        }

        /* ===== HEADER ADMIN ===== */
        .admin-header {
            background: var(--adm-surface2);
            border-bottom: 1px solid var(--adm-border);
            padding: 0.85rem 1.8rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky; top: 0;
            z-index: 99;
        }

        .admin-header-title {
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--adm-text);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .status-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: var(--adm-green);
            display: inline-block;
            animation: blink 1.5s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.3; }
        }

        /* ===== KARTU KPI / STATISTIK ===== */
        .kpi-card {
            background: var(--adm-surface);
            border: 1px solid var(--adm-border);
            border-radius: 10px;
            padding: 1.2rem 1.4rem;
            transition: border-color 0.2s;
        }
        .kpi-card:hover { border-color: #30363d; }

        .kpi-icon {
            width: 40px; height: 40px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 0.8rem;
        }

        .kpi-value {
            font-size: 1.8rem;
            font-weight: 800;
            color: #fff;
            line-height: 1;
            margin-bottom: 0.25rem;
        }

        .kpi-label {
            font-size: 0.75rem;
            color: var(--adm-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .kpi-change {
            font-size: 0.75rem;
            margin-top: 0.5rem;
        }

        /* ===== PANEL/SECTION ===== */
        .admin-panel {
            background: var(--adm-surface);
            border: 1px solid var(--adm-border);
            border-radius: 10px;
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .admin-panel-header {
            background: var(--adm-surface2);
            border-bottom: 1px solid var(--adm-border);
            padding: 0.85rem 1.3rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .admin-panel-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0;
        }

        /* ===== TABEL ADMIN ===== */
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
        }

        .admin-table thead th {
            background: var(--adm-surface2);
            color: var(--adm-text-muted);
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 0.65rem 1rem;
            border-bottom: 1px solid var(--adm-border);
            white-space: nowrap;
        }

        .admin-table tbody tr {
            border-bottom: 1px solid var(--adm-border);
            transition: background 0.15s;
        }

        .admin-table tbody tr:last-child { border-bottom: none; }
        .admin-table tbody tr:hover { background: rgba(255,255,255,0.03); }

        .admin-table tbody td {
            padding: 0.65rem 1rem;
            color: var(--adm-text);
            vertical-align: middle;
        }

        /* Status Badge */
        .status-badge {
            font-size: 0.68rem;
            font-weight: 700;
            padding: 2px 9px;
            border-radius: 10px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .status-active  { background: rgba(63,185,80,0.15);  color: var(--adm-green); }
        .status-pending { background: rgba(210,153,34,0.15); color: var(--adm-amber); }
        .status-banned  { background: rgba(248,81,73,0.15);  color: var(--adm-accent); }
        .status-review  { background: rgba(88,166,255,0.15); color: var(--adm-blue);  }

        /* Tombol aksi tabel */
        .btn-adm {
            font-size: 0.72rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 6px;
            border: 1px solid;
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 3px;
        }

        .btn-adm-danger {
            border-color: rgba(248,81,73,0.4);
            color: var(--adm-accent);
            background: transparent;
        }
        .btn-adm-danger:hover { background: rgba(248,81,73,0.1); color: var(--adm-accent); }

        .btn-adm-primary {
            border-color: rgba(88,166,255,0.4);
            color: var(--adm-blue);
            background: transparent;
        }
        .btn-adm-primary:hover { background: rgba(88,166,255,0.1); color: var(--adm-blue); }

        .btn-adm-success {
            border-color: rgba(63,185,80,0.4);
            color: var(--adm-green);
            background: transparent;
        }
        .btn-adm-success:hover { background: rgba(63,185,80,0.1); color: var(--adm-green); }

        /* Responsif: sembunyikan sidebar di layar kecil */
        @media (max-width: 768px) {
            .admin-sidebar { display: none; }
            .admin-main    { margin-left: 0; }
        }
    </style>
</head>
<body>

    <div class="admin-wrapper">

        {{-- ============================================================
             SIDEBAR NAVIGASI ADMIN
             ============================================================ --}}
        <aside class="admin-sidebar">

            {{-- Logo Admin --}}
            <div class="admin-sidebar-logo">
                <div class="sidebar-logo-icon">🔐</div>
                <div>
                    <div class="sidebar-logo-text">AI-Connect</div>
                    <div class="sidebar-logo-sub">Admin Panel</div>
                </div>
            </div>

            {{-- Nav: Overview --}}
            <div class="sidebar-section-label">Overview</div>

            <a href="#section-dashboard" class="sidebar-nav-link active">
                <span class="nav-icon"><i class="bi bi-speedometer2"></i></span>
                Dashboard
            </a>
            <a href="#section-analytics" class="sidebar-nav-link">
                <span class="nav-icon"><i class="bi bi-graph-up-arrow"></i></span>
                Analitik
            </a>

            {{-- Nav: Konten --}}
            <div class="sidebar-section-label">Manajemen Konten</div>

            <a href="#section-posts" class="sidebar-nav-link">
                <span class="nav-icon"><i class="bi bi-file-earmark-text"></i></span>
                Postingan
                <span class="sidebar-badge bg-primary text-white">128</span>
            </a>
            <a href="#section-reports" class="sidebar-nav-link">
                <span class="nav-icon"><i class="bi bi-flag"></i></span>
                Laporan
                <span class="sidebar-badge" style="background:rgba(248,81,73,0.2); color:var(--adm-accent);">7</span>
            </a>
            <a href="#" class="sidebar-nav-link">
                <span class="nav-icon"><i class="bi bi-chat-square-dots"></i></span>
                Komentar
            </a>
            <a href="#" class="sidebar-nav-link">
                <span class="nav-icon"><i class="bi bi-tags"></i></span>
                Kategori
            </a>

            {{-- Nav: Pengguna --}}
            <div class="sidebar-section-label">Manajemen Pengguna</div>

            <a href="#section-users" class="sidebar-nav-link">
                <span class="nav-icon"><i class="bi bi-people"></i></span>
                Semua User
                <span class="sidebar-badge" style="background:rgba(63,185,80,0.2); color:var(--adm-green);">32K</span>
            </a>
            <a href="#" class="sidebar-nav-link">
                <span class="nav-icon"><i class="bi bi-person-slash"></i></span>
                User Terblokir
            </a>

            {{-- Nav: Sistem --}}
            <div class="sidebar-section-label">Sistem</div>

            <a href="#" class="sidebar-nav-link">
                <span class="nav-icon"><i class="bi bi-gear"></i></span>
                Pengaturan
            </a>
            <a href="#" class="sidebar-nav-link">
                <span class="nav-icon"><i class="bi bi-file-earmark-text"></i></span>
                Log Sistem
            </a>

            {{-- Kembali ke Feed --}}
            <div class="mt-4 px-3 pb-4">
                <a href="{{ url('/') }}" class="btn btn-sm w-100" style="
                    background: rgba(248,81,73,0.1);
                    border: 1px solid rgba(248,81,73,0.3);
                    color: var(--adm-accent);
                    font-size:0.78rem;
                ">
                    <i class="bi bi-box-arrow-left me-1"></i> Kembali ke Feed Publik
                </a>
            </div>

        </aside>
        {{-- /SIDEBAR --}}


        {{-- ============================================================
             AREA KONTEN UTAMA ADMIN
             ============================================================ --}}
        <div class="admin-main">

            {{-- Header Admin --}}
            <header class="admin-header">
                <div class="admin-header-title">
                    <span class="status-dot"></span>
                    Panel Admin — AI-Connect
                    <span style="font-size:0.72rem; color:var(--adm-text-muted); margin-left:0.5rem;">
                        / Dashboard
                    </span>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <span style="font-size:0.75rem; color:var(--adm-text-muted);">
                        <i class="bi bi-clock me-1"></i>
                        <span id="admin-clock">--:--:--</span>
                    </span>
                    <div class="d-flex align-items-center gap-2">
                        <img
                            src="https://ui-avatars.com/api/?name=Super+Admin&background=f85149&color=fff&size=32"
                            alt="Admin"
                            style="width:30px;height:30px;border-radius:50%;border:2px solid var(--adm-border);"
                        >
                        <span style="font-size:0.8rem; font-weight:600; color:#fff;">Super Admin</span>
                        <span class="status-badge status-active">Root</span>
                    </div>
                </div>
            </header>
            {{-- /HEADER --}}


            {{-- Konten Panel --}}
            <div class="p-4" id="section-dashboard">

                {{-- Banner peringatan akses terbatas --}}
                <div class="alert d-flex align-items-center gap-3 mb-4" style="
                    background: rgba(248,81,73,0.08);
                    border: 1px solid rgba(248,81,73,0.25);
                    border-radius: 10px;
                    color: #fca5a5;
                    font-size: 0.82rem;
                " role="alert">
                    <i class="bi bi-shield-exclamation fs-4" style="color:var(--adm-accent); flex-shrink:0;"></i>
                    <div>
                        <strong style="color:var(--adm-accent);">AKSES TERBATAS</strong> — Halaman ini bersifat rahasia dan hanya untuk administrator resmi.
                        Semua aktivitas di halaman ini dicatat dalam log sistem.
                        Jika Anda bukan admin, segera <a href="{{ url('/') }}" style="color:var(--adm-accent);">kembali ke halaman utama</a>.
                    </div>
                </div>


                {{-- ====================================================
                     KARTU KPI STATISTIK UTAMA
                     ==================================================== --}}
                <div class="row g-3 mb-4">

                    <div class="col-6 col-xl-3">
                        <div class="kpi-card">
                            <div class="kpi-icon" style="background:rgba(88,166,255,0.12);">📝</div>
                            <div class="kpi-value">8,412</div>
                            <div class="kpi-label">Total Postingan</div>
                            <div class="kpi-change" style="color:var(--adm-green);">
                                <i class="bi bi-arrow-up-short"></i> +24 hari ini
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-xl-3">
                        <div class="kpi-card">
                            <div class="kpi-icon" style="background:rgba(63,185,80,0.12);">👥</div>
                            <div class="kpi-value">32,841</div>
                            <div class="kpi-label">Total Pengguna</div>
                            <div class="kpi-change" style="color:var(--adm-green);">
                                <i class="bi bi-arrow-up-short"></i> +147 minggu ini
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-xl-3">
                        <div class="kpi-card">
                            <div class="kpi-icon" style="background:rgba(248,81,73,0.12);">🚩</div>
                            <div class="kpi-value">7</div>
                            <div class="kpi-label">Laporan Menunggu</div>
                            <div class="kpi-change" style="color:var(--adm-accent);">
                                <i class="bi bi-exclamation-triangle"></i> Segera ditinjau
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-xl-3">
                        <div class="kpi-card">
                            <div class="kpi-icon" style="background:rgba(210,153,34,0.12);">💬</div>
                            <div class="kpi-value">156K</div>
                            <div class="kpi-label">Total Komentar</div>
                            <div class="kpi-change" style="color:var(--adm-green);">
                                <i class="bi bi-arrow-up-short"></i> +892 hari ini
                            </div>
                        </div>
                    </div>

                </div>
                {{-- /KPI --}}


                {{-- ====================================================
                     TABEL 1: RINGKASAN POSTINGAN MASUK TERBARU
                     ==================================================== --}}
                <div class="admin-panel" id="section-posts">
                    <div class="admin-panel-header">
                        <h2 class="admin-panel-title">
                            <i class="bi bi-file-earmark-text" style="color:var(--adm-blue);"></i>
                            Postingan Masuk Terbaru
                        </h2>
                        <div class="d-flex gap-2">
                            <span style="font-size:0.75rem; color:var(--adm-text-muted);">128 total</span>
                            <a href="#" class="btn-adm btn-adm-primary">
                                <i class="bi bi-arrow-clockwise"></i> Refresh
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Judul Postingan</th>
                                    <th>Penulis</th>
                                    <th>Kategori</th>
                                    <th>Vote</th>
                                    <th>Komentar</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Baris 1 --}}
                                <tr>
                                    <td style="color:var(--adm-text-muted);">001</td>
                                    <td>
                                        <a href="#" style="color:var(--adm-blue); text-decoration:none; font-weight:600;">
                                            Implementasi Gradient Boosting dari Nol
                                        </a>
                                    </td>
                                    <td>Rina Setiawan</td>
                                    <td><span class="status-badge status-review">ML</span></td>
                                    <td style="color:var(--adm-green);">↑ 248</td>
                                    <td>42</td>
                                    <td style="color:var(--adm-text-muted);">2025-06-14</td>
                                    <td><span class="status-badge status-active">Publik</span></td>
                                    <td>
                                        <a href="#" class="btn-adm btn-adm-primary me-1">
                                            <i class="bi bi-eye"></i> Lihat
                                        </a>
                                        <a href="#" class="btn-adm btn-adm-danger">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                                {{-- Baris 2 --}}
                                <tr>
                                    <td style="color:var(--adm-text-muted);">002</td>
                                    <td>
                                        <a href="#" style="color:var(--adm-blue); text-decoration:none; font-weight:600;">
                                            Fine-tuning GPT-2 untuk Bahasa Indonesia
                                        </a>
                                    </td>
                                    <td>Dimas Pratama</td>
                                    <td><span class="status-badge status-review">NLP</span></td>
                                    <td style="color:var(--adm-green);">↑ 183</td>
                                    <td>29</td>
                                    <td style="color:var(--adm-text-muted);">2025-06-14</td>
                                    <td><span class="status-badge status-active">Publik</span></td>
                                    <td>
                                        <a href="#" class="btn-adm btn-adm-primary me-1">
                                            <i class="bi bi-eye"></i> Lihat
                                        </a>
                                        <a href="#" class="btn-adm btn-adm-danger">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                                {{-- Baris 3 --}}
                                <tr>
                                    <td style="color:var(--adm-text-muted);">003</td>
                                    <td>
                                        <a href="#" style="color:var(--adm-blue); text-decoration:none; font-weight:600;">
                                            Real-time Deteksi Gestur Tangan MediaPipe
                                        </a>
                                    </td>
                                    <td>Nadia Kusuma</td>
                                    <td><span class="status-badge status-review">CV</span></td>
                                    <td style="color:var(--adm-green);">↑ 512</td>
                                    <td>87</td>
                                    <td style="color:var(--adm-text-muted);">2025-06-13</td>
                                    <td><span class="status-badge status-active">Publik</span></td>
                                    <td>
                                        <a href="#" class="btn-adm btn-adm-primary me-1">
                                            <i class="bi bi-eye"></i> Lihat
                                        </a>
                                        <a href="#" class="btn-adm btn-adm-danger">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                                {{-- Baris 4 --}}
                                <tr>
                                    <td style="color:var(--adm-text-muted);">004</td>
                                    <td>
                                        <a href="#" style="color:var(--adm-amber); text-decoration:none; font-weight:600;">
                                            ⚠ [Dilaporkan] Cara Bypass Filter Konten AI
                                        </a>
                                    </td>
                                    <td style="color:var(--adm-accent);">user_anon_x</td>
                                    <td><span class="status-badge status-review">LLM</span></td>
                                    <td style="color:var(--adm-accent);">↓ -14</td>
                                    <td>3</td>
                                    <td style="color:var(--adm-text-muted);">2025-06-15</td>
                                    <td><span class="status-badge status-pending">Pending</span></td>
                                    <td>
                                        <a href="#" class="btn-adm btn-adm-success me-1">
                                            <i class="bi bi-check-lg"></i> Approve
                                        </a>
                                        <a href="#" class="btn-adm btn-adm-danger">
                                            <i class="bi bi-ban"></i> Hapus
                                        </a>
                                    </td>
                                </tr>

                                {{-- Baris 5 --}}
                                <tr>
                                    <td style="color:var(--adm-text-muted);">005</td>
                                    <td>
                                        <a href="#" style="color:var(--adm-blue); text-decoration:none; font-weight:600;">
                                            Melatih DQN untuk Bermain Snake Game
                                        </a>
                                    </td>
                                    <td>Budi Hartono</td>
                                    <td><span class="status-badge status-review">RL</span></td>
                                    <td style="color:var(--adm-green);">↑ 319</td>
                                    <td>55</td>
                                    <td style="color:var(--adm-text-muted);">2025-06-12</td>
                                    <td><span class="status-badge status-active">Publik</span></td>
                                    <td>
                                        <a href="#" class="btn-adm btn-adm-primary me-1">
                                            <i class="bi bi-eye"></i> Lihat
                                        </a>
                                        <a href="#" class="btn-adm btn-adm-danger">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- /TABEL POSTINGAN --}}


                <div class="row g-3">

                    {{-- ================================================
                         TABEL 2: MANAJEMEN PENGGUNA
                         ================================================ --}}
                    <div class="col-lg-6">
                        <div class="admin-panel h-100" id="section-users">
                            <div class="admin-panel-header">
                                <h2 class="admin-panel-title">
                                    <i class="bi bi-people" style="color:var(--adm-green);"></i>
                                    Manajemen Pengguna
                                </h2>
                                <span style="font-size:0.75rem; color:var(--adm-text-muted);">32,841 user</span>
                            </div>

                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Pengguna</th>
                                        <th>Bergabung</th>
                                        <th>Post</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="https://ui-avatars.com/api/?name=Rina+S&background=6366f1&color=fff&size=24" style="width:24px;height:24px;border-radius:50%;" alt="">
                                                <span>Rina Setiawan</span>
                                            </div>
                                        </td>
                                        <td style="color:var(--adm-text-muted);">Jan 2024</td>
                                        <td>47</td>
                                        <td><span class="status-badge status-active">Aktif</span></td>
                                        <td><a href="#" class="btn-adm btn-adm-danger"><i class="bi bi-person-slash"></i> Ban</a></td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="https://ui-avatars.com/api/?name=Dimas+P&background=06b6d4&color=fff&size=24" style="width:24px;height:24px;border-radius:50%;" alt="">
                                                <span>Dimas Pratama</span>
                                            </div>
                                        </td>
                                        <td style="color:var(--adm-text-muted);">Mar 2024</td>
                                        <td>31</td>
                                        <td><span class="status-badge status-active">Aktif</span></td>
                                        <td><a href="#" class="btn-adm btn-adm-danger"><i class="bi bi-person-slash"></i> Ban</a></td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="https://ui-avatars.com/api/?name=Anon+X&background=f85149&color=fff&size=24" style="width:24px;height:24px;border-radius:50%;" alt="">
                                                <span style="color:var(--adm-accent);">user_anon_x</span>
                                            </div>
                                        </td>
                                        <td style="color:var(--adm-text-muted);">Jun 2025</td>
                                        <td>2</td>
                                        <td><span class="status-badge status-pending">Tinjau</span></td>
                                        <td><a href="#" class="btn-adm btn-adm-danger"><i class="bi bi-person-slash"></i> Ban</a></td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="https://ui-avatars.com/api/?name=Spam+Bot&background=64748b&color=fff&size=24" style="width:24px;height:24px;border-radius:50%;" alt="">
                                                <span style="color:var(--adm-text-muted);">spam_bot_99</span>
                                            </div>
                                        </td>
                                        <td style="color:var(--adm-text-muted);">Jun 2025</td>
                                        <td>0</td>
                                        <td><span class="status-badge status-banned">Banned</span></td>
                                        <td><a href="#" class="btn-adm btn-adm-success"><i class="bi bi-person-check"></i> Unban</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- ================================================
                         TABEL 3: LAPORAN KONTEN
                         ================================================ --}}
                    <div class="col-lg-6">
                        <div class="admin-panel h-100" id="section-reports">
                            <div class="admin-panel-header">
                                <h2 class="admin-panel-title">
                                    <i class="bi bi-flag" style="color:var(--adm-accent);"></i>
                                    Laporan Konten
                                </h2>
                                <span class="status-badge status-pending">7 Baru</span>
                            </div>

                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Konten</th>
                                        <th>Dilaporkan oleh</th>
                                        <th>Alasan</th>
                                        <th>Tgl</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <a href="#" style="color:var(--adm-blue); text-decoration:none;">
                                                Post #004
                                            </a>
                                        </td>
                                        <td>3 pengguna</td>
                                        <td><span class="status-badge status-banned">Berbahaya</span></td>
                                        <td style="color:var(--adm-text-muted);">15 Jun</td>
                                        <td class="d-flex gap-1 flex-wrap">
                                            <a href="#" class="btn-adm btn-adm-danger">Hapus</a>
                                            <a href="#" class="btn-adm btn-adm-primary">Abaikan</a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <a href="#" style="color:var(--adm-blue); text-decoration:none;">
                                                Komentar #728
                                            </a>
                                        </td>
                                        <td>1 pengguna</td>
                                        <td><span class="status-badge status-pending">Spam</span></td>
                                        <td style="color:var(--adm-text-muted);">14 Jun</td>
                                        <td class="d-flex gap-1 flex-wrap">
                                            <a href="#" class="btn-adm btn-adm-danger">Hapus</a>
                                            <a href="#" class="btn-adm btn-adm-primary">Abaikan</a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <a href="#" style="color:var(--adm-blue); text-decoration:none;">
                                                Post #391
                                            </a>
                                        </td>
                                        <td>2 pengguna</td>
                                        <td><span class="status-badge status-pending">Hoaks</span></td>
                                        <td style="color:var(--adm-text-muted);">13 Jun</td>
                                        <td class="d-flex gap-1 flex-wrap">
                                            <a href="#" class="btn-adm btn-adm-danger">Hapus</a>
                                            <a href="#" class="btn-adm btn-adm-primary">Abaikan</a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <a href="#" style="color:var(--adm-blue); text-decoration:none;">
                                                User #anon_x
                                            </a>
                                        </td>
                                        <td>5 pengguna</td>
                                        <td><span class="status-badge status-banned">Penyalahgunaan</span></td>
                                        <td style="color:var(--adm-text-muted);">15 Jun</td>
                                        <td class="d-flex gap-1 flex-wrap">
                                            <a href="#" class="btn-adm btn-adm-danger">Ban</a>
                                            <a href="#" class="btn-adm btn-adm-primary">Tinjau</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                {{-- /TABEL USER + LAPORAN --}}


                {{-- Footer Admin --}}
                <div class="mt-4 pt-3 border-top text-center" style="border-color:var(--adm-border) !important; font-size:0.72rem; color:var(--adm-text-muted);">
                    AI-Connect Admin Panel · Sesi dimulai: <span id="session-start"></span> ·
                    Semua aktivitas tercatat dalam log sistem
                </div>

            </div>
            {{-- /KONTEN --}}

        </div>
        {{-- /ADMIN MAIN --}}

    </div>
    {{-- /WRAPPER --}}


    {{-- Bootstrap 5 JS --}}
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc4s9bIOgUxi8T/jzmE2GXUf7Fy6uRx8MrHpKJEPTkm"
        crossorigin="anonymous"
    ></script>

    <script>
        /**
         * JAM REAL-TIME ADMIN
         * -------------------
         * Menampilkan jam digital yang terus diperbarui setiap detik
         * di header panel admin, menggunakan setInterval().
         *
         * Ini berguna untuk monitoring real-time agar admin tahu
         * waktu saat ini tanpa harus keluar dari panel.
         */
        function updateClock() {
            const now  = new Date();
            const hh   = String(now.getHours()).padStart(2, '0');   // Format 2 digit
            const mm   = String(now.getMinutes()).padStart(2, '0');
            const ss   = String(now.getSeconds()).padStart(2, '0');
            const clockEl = document.getElementById('admin-clock');
            if (clockEl) clockEl.textContent = `${hh}:${mm}:${ss}`;
        }

        // Jalankan segera, lalu ulangi setiap 1000ms (1 detik)
        updateClock();
        setInterval(updateClock, 1000);

        /**
         * WAKTU MULAI SESI
         * ----------------
         * Catat dan tampilkan waktu pertama kali admin membuka panel.
         */
        const sessionStartEl = document.getElementById('session-start');
        if (sessionStartEl) {
            const now = new Date();
            sessionStartEl.textContent = now.toLocaleString('id-ID', {
                day: 'numeric', month: 'short', year: 'numeric',
                hour: '2-digit', minute: '2-digit'
            });
        }

        /**
         * KONFIRMASI AKSI DESTRUKTIF (Ban / Hapus)
         * -----------------------------------------
         * Semua tombol yang mengandung kata "Ban" atau "Hapus" akan
         * meminta konfirmasi sebelum melanjutkan aksi, untuk mencegah
         * admin tidak sengaja menghapus konten atau memblokir user.
         */
        document.querySelectorAll('.btn-adm-danger').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                const action = this.textContent.trim();
                const confirmed = confirm(`⚠️ Konfirmasi Aksi Admin\n\nAnda akan melakukan: "${action}"\nApakah Anda yakin? Tindakan ini mungkin tidak dapat dibatalkan.`);
                if (!confirmed) {
                    e.preventDefault(); // Batalkan navigasi/aksi jika tidak dikonfirmasi
                }
            });
        });

        /**
         * EVENT LISTENER RAHASIA (JUGA ADA DI HALAMAN ADMIN)
         * ----------------------------------------------------
         * Karena admin.blade.php tidak meng-extend layouts.app,
         * script Ctrl+Alt+A dari layout induk tidak tersedia di sini.
         * Maka kita tambahkan ulang di sini untuk konsistensi.
         * (Di halaman admin, shortcut ini tidak akan redirect ke mana-mana
         *  atau bisa diubah untuk fungsi lain seperti refresh data.)
         */
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.altKey && e.key === 'a') {
                e.preventDefault();
                // Di halaman admin, kita tampilkan pesan saja (sudah ada di sini)
                alert('ℹ️ Anda sudah berada di Panel Admin.');
            }
        });
    </script>

</body>
</html>
