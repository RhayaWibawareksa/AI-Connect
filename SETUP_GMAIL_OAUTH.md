# 🔐 Setup Gmail OAuth & MySQL untuk AI-Connect

## Langkah 1: Migrasi dari SQLite ke MySQL XAMPP

### 1.1 Verifikasi MySQL XAMPP Sudah Berjalan
- Buka XAMPP Control Panel
- Pastikan **MySQL** berstatus "Running" (tombol START berwarna hijau)
- Buka http://localhost/phpmyadmin untuk verifikasi

### 1.2 Buat Database di MySQL
Di phpMyAdmin:
1. Klik tab **SQL**
2. Jalankan query ini:
```sql
CREATE DATABASE IF NOT EXISTS db_ai_connect CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```
3. Klik **Execute** ✅

### 1.3 Update File .env
File `.env` sudah dikonfigurasi untuk MySQL:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_ai_connect
DB_USERNAME=root
DB_PASSWORD=
```

### 1.4 Restart Laravel App & Jalankan Migration
Buka Terminal di folder project:
```bash
# Hentikan app jika sedang running (Ctrl+C)
# Jalankan migration dari SQLite ke MySQL
php artisan migrate:fresh --seed

# Mulai server
php artisan serve
```

**Verifikasi:** Buka http://localhost:8000/dashboard - seharusnya semua 4 posts sudah tampil dari MySQL ✅

---

## Langkah 2: Install Laravel Socialite (untuk OAuth Google)

Buka Terminal di folder project:
```bash
composer require laravel/socialite
```

Tunggu hingga selesai... ⏳

---

## Langkah 3: Setup Google OAuth Credentials

### 3.1 Buka Google Cloud Console
Buka: https://console.cloud.google.com/

### 3.2 Buat Project Baru
1. Klik **Select a Project** → **NEW PROJECT**
2. Nama: `AI-Connect-Local` 
3. Klik **CREATE**
4. Tunggu project selesai dibuat

### 3.3 Setup OAuth Consent Screen
1. Di sidebar, klik **APIs & Services** → **OAuth consent screen**
2. Pilih **External** → **CREATE**
3. Isi form:
   - **App name**: `AI-Connect`
   - **User support email**: (email Anda)
   - **Developer contact info**: (email Anda)
4. Klik **SAVE AND CONTINUE** sampai selesai

### 3.4 Buat OAuth 2.0 Credentials
1. Klik **Credentials** (di sidebar)
2. Klik **+ CREATE CREDENTIALS** → **OAuth client ID**
3. Pilih **Web application**
4. Isi **Name**: `Local Dev`
5. Di **Authorized redirect URIs**, klik **ADD URI** dan masukkan:
   ```
   http://localhost:8000/auth/google/callback
   ```
6. Klik **CREATE**
7. **Salin Client ID dan Client Secret** ✅

---

## Langkah 4: Update .env dengan Google Credentials

Edit file `.env` dan ganti:
```env
GOOGLE_CLIENT_ID=xxxx_your_client_id_xxxx.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-xxxx_your_secret_xxxx
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

Paste Client ID dan Secret yang Anda copy dari Google Console ⬆️

---

## Langkah 5: Jalankan Migration untuk Google OAuth Fields

Buka Terminal:
```bash
php artisan migrate
```

Ini akan menambahkan kolom ke tabel users:
- `google_id`
- `google_token` 
- `profile_photo_url`
- `last_login_at`

---

## Langkah 6: Restart App & Test Gmail Login

```bash
# Hentikan app (Ctrl+C)
php artisan serve
```

Buka: http://localhost:8000/login

Klik **Masuk dengan Google** 🔵

### Expected Flow:
1. ✅ Redirect ke Google login
2. ✅ Pilih akun Google Anda
3. ✅ Grant permission (jika diminta)
4. ✅ Redirect kembali ke dashboard
5. ✅ User baru dibuat di database dengan google_id
6. ✅ Authenticated sebagai user tersebut

---

## Langkah 7: Test Membuat Post sebagai User Terautenttikasi

1. Setelah login dengan Gmail, klik **Buat Post**
2. Isi form:
   - **Judul**: Test Post from Gmail
   - **Kategori**: Machine Learning
   - **Deskripsi**: Testing Gmail OAuth integration
3. Klik **Post** ✅
4. Post baru seharusnya muncul di dashboard dengan nama Anda dan `user_id` = user yang login

---

## Troubleshooting

### ❌ "Invalid client" saat klik "Masuk dengan Google"
- Verifikasi GOOGLE_CLIENT_ID dan GOOGLE_CLIENT_SECRET di .env sudah benar
- Buat ulang credentials di Google Console

### ❌ "Connection refused" ke MySQL
- Verifikasi MySQL XAMPP status = Running
- Cek DB_HOST, DB_PORT, DB_USERNAME, DB_PASSWORD di .env

### ❌ "No application encryption key" 
- Jalankan: `php artisan key:generate`

### ❌ "SQLSTATE[HY000]: General error" saat migrate
- Database belum ada atau tidak bisa diakses
- Jalankan `php artisan migrate:fresh --seed` untuk reset

---

## Fitur yang Sudah Aktif ✅

- ✅ Dashboard dengan 4 filter tabs (Popular, Latest, Discussed, Saved)
- ✅ Vote & Bookmark buttons
- ✅ Comments section
- ✅ Category sidebar
- ✅ Top contributors ranking
- ✅ Search box
- ✅ **NEW: Gmail OAuth Login**
- ✅ **NEW: MySQL Database Storage**
- ✅ Post creation oleh authenticated users

---

## Next Steps (Optional)

1. **Deploy ke Azure** - Setup App Service + Azure Database for MySQL
2. **Email Notifications** - Setup queue untuk email notifications
3. **Admin Panel** - Manage posts, users, categories
4. **Mobile App** - API endpoints untuk mobile client

---

**Questions?** Check Laravel Docs:
- https://laravel.com/docs/socialite
- https://laravel.com/docs/migrations

