📋 **SETUP CHECKLIST - Gmail OAuth + MySQL untuk AI-Connect**

---

## ✅ STEP 1: MySQL XAMPP Setup
- [ ] Buka XAMPP Control Panel
- [ ] Pastikan MySQL status = **Running** 🟢
- [ ] Buka http://localhost/phpmyadmin
- [ ] Jalankan query di tab SQL:
  ```sql
  CREATE DATABASE IF NOT EXISTS db_ai_connect CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
  ```
- [ ] Klik **Execute** ✅

---

## ✅ STEP 2: Konfigurasi .env
File `.env` sudah dikonfigurasi otomatis dengan:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_ai_connect
DB_USERNAME=root
DB_PASSWORD=
```

---

## ✅ STEP 3: Install Laravel Socialite
Buka Terminal di folder project:
```bash
composer require laravel/socialite
```
Tunggu hingga selesai ⏳

---

## ✅ STEP 4: Setup Google Cloud Console

### 4.1 Buka Google Cloud
- [ ] Buka https://console.cloud.google.com/
- [ ] Klik **Select a Project** → **NEW PROJECT**
- [ ] Nama: `AI-Connect-Local`
- [ ] Klik **CREATE** dan tunggu

### 4.2 Setup OAuth Consent Screen
- [ ] Di sidebar: **APIs & Services** → **OAuth consent screen**
- [ ] Pilih **External** → **CREATE**
- [ ] Isi:
  - App name: `AI-Connect`
  - User support email: (email Anda)
  - Developer contact: (email Anda)
- [ ] Klik **SAVE AND CONTINUE** sampai selesai

### 4.3 Buat OAuth Credentials
- [ ] Klik **Credentials** (sidebar)
- [ ] Klik **+ CREATE CREDENTIALS** → **OAuth client ID**
- [ ] Pilih **Web application**
- [ ] Name: `Local Dev`
- [ ] Di **Authorized redirect URIs**, klik **ADD URI**:
  ```
  http://localhost:8000/auth/google/callback
  ```
- [ ] Klik **CREATE**
- [ ] **SALIN Client ID dan Client Secret** 📋

---

## ✅ STEP 5: Update .env dengan Google Credentials
Edit `.env`, cari bagian Google OAuth:
```env
GOOGLE_CLIENT_ID=xxxx_paste_client_id_xxxx
GOOGLE_CLIENT_SECRET=xxxx_paste_client_secret_xxxx
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

**Paste nilai dari Google Cloud Console yang Anda salin** ⬆️

---

## ✅ STEP 6: Database Migration & Seeding
Buka Terminal:
```bash
# Hentikan app jika sedang berjalan (Ctrl+C)

# Migrate data dari SQLite ke MySQL
php artisan migrate:fresh --seed

# Tambahkan Google OAuth fields
php artisan migrate
```

---

## ✅ STEP 7: Restart & Test
```bash
php artisan serve
```

Buka: http://localhost:8000/dashboard

✅ Verify:
- Semua 4 posts tampil (dari MySQL sekarang, bukan SQLite)
- Dashboard responsive dan tidak ada error
- Sidebar categories dan top contributors muncul

---

## ✅ STEP 8: Test Gmail Login

1. Klik **Logout** (jika sudah login)
2. Buka http://localhost:8000/login
3. Klik **Masuk dengan Google** 🔵
4. Pilih akun Google Anda
5. Klik **Continue/Allow** jika diminta permission
6. ✅ Seharusnya redirect ke `/dashboard`
7. ✅ Nama user di navbar seharusnya berubah dengan nama Google Anda

---

## ✅ STEP 9: Test Membuat Post

Setelah berhasil login Gmail:
1. Klik **Buat Post**
2. Isi form:
   - **Judul**: Test Gmail OAuth
   - **Kategori**: Machine Learning
   - **Deskripsi**: Testing Gmail integration
3. Klik **Post**
4. ✅ Post baru seharusnya muncul di dashboard
5. ✅ Author seharusnya nama Google Anda
6. ✅ Di database, `user_id` = ID user yang login

---

## 🆘 TROUBLESHOOTING

### ❌ "Invalid Client" saat klik "Masuk dengan Google"
**Solution:**
- Verifikasi GOOGLE_CLIENT_ID dan SECRET di .env sudah benar
- Reload page, hapus cache browser (Ctrl+Shift+Delete)
- Buat ulang credentials di Google Console

### ❌ "Connection refused" atau "No such file/database"
**Solution:**
- Verifikasi MySQL XAMPP running (bukan error/stopped)
- Check `.env` DB credentials benar
- Create database di phpMyAdmin jika belum ada
- Run: `php artisan migrate:fresh --seed`

### ❌ "No application encryption key"
**Solution:**
```bash
php artisan key:generate
```

### ❌ "SQLSTATE[HY000]" atau "General error" saat migrate
**Solution:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan migrate:fresh --seed
```

### ❌ "Laravel can't find GoogleAuthController"
**Solution:**
- Verifikasi file ada: `app/Http/Controllers/GoogleAuthController.php`
- Run: `composer dump-autoload`
- Restart app

---

## ✨ FITUR YANG SUDAH AKTIF

✅ Dashboard dengan 4 filter tabs (Popular, Latest, Discussed, Saved)
✅ Vote & Bookmark buttons dengan live counts
✅ Comments section dengan user avatars
✅ Category sidebar dengan post counts
✅ Top contributors ranking
✅ Search functionality
✅ **NEW: Gmail OAuth Login** 🔵
✅ **NEW: MySQL Database** 🗄️
✅ Post creation oleh authenticated users only
✅ Comment creation oleh authenticated users only

---

## 📞 NEXT SUPPORT

- Jika ada error, check file: `storage/logs/laravel.log`
- Semua file sudah tersedia:
  - ✅ Migration file: `database/migrations/2026_07_07_100000_add_google_auth_to_users.php`
  - ✅ OAuth Controller: `app/Http/Controllers/GoogleAuthController.php`
  - ✅ Routes updated: `routes/web.php`
  - ✅ Config updated: `config/services.php`
  - ✅ Login UI updated: `resources/views/auth/login.blade.php`
  - ✅ Model updated: `app/Models/User.php`
  - ✅ Controller updated: `app/Http/Controllers/PostViewController.php`

---

## 🎯 SETELAH SEMUA SELESAI

**Optional Advanced Features:**

1. **Email Notifications** - Setup queue untuk notifikasi
2. **Admin Dashboard** - Manage users, posts, categories
3. **API Endpoints** - Untuk mobile app development
4. **Deploy to Azure** - Production deployment
5. **Social Features** - Follow users, direct messages

---

**Ready to start? Mulai dari STEP 1 di atas! 🚀**

