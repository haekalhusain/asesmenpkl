# AsesmenPKL – Sistem Peminjaman Lab TEFA PPLG

Aplikasi web manajemen peminjaman peralatan Lab TEFA PPLG berbasis **Laravel 12** + **MySQL**.

---

## 🎨 Tampilan
- Tema **Dark Gold** (hitam + emas) seperti desain referensi
- Font: Rajdhani (heading) + Exo 2 (body)
- Responsif untuk mobile

---

## ✅ Fitur Lengkap
- 🔐 Login & Logout
- 📊 Dashboard Statistik (simpel)
- 👤 CRUD Data Pengguna (+ upload)
- 🔧 CRUD Data Peralatan (+ upload foto)
- 📋 CRUD Data Peminjaman
- 🔍 Pencarian & Filter Data
- 🔢 Manajemen Stok otomatis saat pinjam/kembali

---

## 🚀 Cara Instalasi

### 1. Persyaratan
- PHP >= 8.2
- Composer
- MySQL
- Laravel 12

### 2. Clone / Pindahkan project ke htdocs / www
```bash
# Jika dari zip, extract ke folder xampp/htdocs/asesmen-pkl
cd asesmen-pkl
```

### 3. Install dependensi
```bash
composer install
```

### 4. Konfigurasi .env
```bash
cp .env.example .env
php artisan key:generate
```

Edit file `.env`:
```env
APP_NAME="AsesmenPKL"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=asesmen_pkl
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Buat database di MySQL
```sql
CREATE DATABASE asesmen_pkl;
```

### 6. Jalankan migrasi + seeder
```bash
php artisan migrate --seed
```

### 7. Buat symbolic link storage (untuk upload foto)
```bash
php artisan storage:link
```

### 8. Jalankan server
```bash
php artisan serve
```

Buka browser: **http://localhost:8000**

---

## 🔑 Akun Default (dari Seeder)

| Role  | Email                  | Password   |
|-------|------------------------|------------|
| Admin | admin@asesmen.test     | password   |
| User  | budi@siswa.test        | password   |

---

## 📁 Struktur File Penting

```
app/
├── Http/Controllers/
│   ├── AuthController.php       ← Login/Logout
│   ├── DashboardController.php  ← Dashboard statistik
│   ├── PenggunaController.php   ← CRUD Pengguna
│   ├── PeralatanController.php  ← CRUD Peralatan + Upload Foto
│   └── PeminjamanController.php ← CRUD Peminjaman + stok otomatis
├── Models/
│   ├── User.php        ← Model pengguna (Eloquent)
│   ├── Peralatan.php   ← Model peralatan
│   └── Peminjaman.php  ← Model peminjaman (relasi ke User & Peralatan)

database/
├── migrations/         ← Struktur tabel (users, peralatans, peminjamans)
└── seeders/
    └── DatabaseSeeder.php  ← Data dummy

resources/views/
├── layouts/            ← Template utama (app.blade.php, auth.blade.php)
├── auth/               ← Halaman login
├── dashboard/          ← Dashboard
├── pengguna/           ← CRUD pengguna (index, create, edit, show)
├── peralatan/          ← CRUD peralatan (index, create, edit, show)
└── peminjaman/         ← CRUD peminjaman (index, create, edit, show)

public/css/
└── app.css             ← Stylesheet utama (Dark Gold Theme)

routes/
└── web.php             ← Semua routing aplikasi
```

---

## 📊 Relasi Database

```
users (pengguna)
  └─── peminjamans (1 user → banyak peminjaman)

peralatans
  └─── peminjamans (1 peralatan → banyak peminjaman)

peminjamans
  ├─── belongsTo users
  └─── belongsTo peralatans
```

---

## 💡 Catatan Penting

1. **Stok otomatis**: Saat tambah peminjaman status "dipinjam", stok berkurang otomatis. Saat status berubah ke "dikembalikan", stok bertambah kembali.

2. **Upload foto**: Pastikan sudah jalankan `php artisan storage:link` agar foto peralatan tampil.

3. **Pagination**: Semua tabel sudah ada pagination 10 data per halaman.

4. **Pencarian**: Tersedia di semua halaman index (pengguna, peralatan, peminjaman).

---

## 📝 Penilaian Kisi-Kisi

| Komponen                    | Bobot | Status |
|-----------------------------|-------|--------|
| Desain Database & Relasi    | 20%   | ✅     |
| CRUD Data Pengguna          | 15%   | ✅     |
| CRUD Data Peralatan         | 15%   | ✅     |
| CRUD Data Peminjaman        | 20%   | ✅     |
| Implementasi Laravel 12     | 20%   | ✅     |
| UI/UX & Kerapian Tampilan   | 10%   | ✅     |
| **Bonus: Login & Logout**   | +10   | ✅     |
| **Bonus: Dashboard Statistik** | +10 | ✅    |
| **Bonus: Pencarian & Filter** | +10 | ✅    |
| **Bonus: Upload Foto**      | +10   | ✅     |
