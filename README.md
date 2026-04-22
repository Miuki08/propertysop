# Aplikasi Manajemen Bengkel Motor - Laravel + Filament

Aplikasi web untuk manajemen bengkel motor dengan dua jenis pengguna: **Admin** dan **Mekanik**.  
Dibangun menggunakan Laravel dan Filament Panel untuk mempercepat pembuatan dashboard yang rapi dan modern. [web:281][web:289]

## Fitur Utama

- Autentikasi user dengan role `admin` dan `mechanic`.
- Panel **Admin**:
  - Kelola pelanggan (customer).
  - Kelola motor pelanggan.
  - Kelola sparepart.
  - Kelola booking servis.
  - Kelola pembelian/purchase sparepart.
  - Laporan stok menipis.
  - Export laporan (misal per bulan).
- Panel **Mechanic**:
  - Melihat dan mengelola booking yang menjadi tanggung jawabnya.
  - Mengelola penggunaan sparepart saat servis.

## Teknologi

- PHP >= 8.1
- Laravel 10/11
- MySQL / MariaDB
- Filament Panel ^3.x [web:280][web:283]
- Composer

---

## Persiapan Lingkungan

Pastikan sudah terinstall:

- PHP (8.1 atau lebih baru)
- Composer
- MySQL / MariaDB
- Git (opsional, jika clone repo) [web:277][web:279]

---

## Instalasi

### 1. Clone & masuk ke project

```bash
git clone <url-repo-ini>.git
cd <nama-folder-project>
```

Jika kamu tidak clone, buat project baru Laravel terlebih dahulu:

```bash
composer create-project laravel/laravel:^10.0 bengkel-app
cd bengkel-app
```
[web:279][web:277]

### 2. Install dependency PHP

```bash
composer install
```

Atau jika kamu baru buat project Laravel, command ini sudah termasuk saat create-project. [web:279]

### 3. Setup environment

Copy file `.env`:

```bash
cp .env.example .env
```

Edit `.env`:

- Atur koneksi database (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
- Sesuaikan `APP_URL`, misal: `http://127.0.0.1:8001`. [web:277][web:290]

Generate key aplikasi:

```bash
php artisan key:generate
```

### 4. Install Filament Panel

Jika belum terinstall:

```bash
composer require filament/filament:"^3.3" -W
php artisan filament:install --panels
```
[web:280][web:283]

Ini akan membuat konfigurasi panel dasar untuk Filament.

### 5. Migrasi database

Jalankan migration:

```bash
php artisan migrate
```
[web:277]

Jika project ini sudah menyediakan seeder (misalnya untuk user admin dan mechanic), jalankan:

```bash
php artisan db:seed
```

Atau:

```bash
php artisan migrate --seed
```

---

## Panel & Role

### 1. Membuat user Filament

Jika belum ada user admin:

```bash
php artisan make:filament-user
```

Ikuti prompt untuk mengisi:

- Nama
- Email
- Password
- Role (sesuaikan dengan implementasi di project ini, misalnya `admin` atau `mechanic`). [web:280]

Atau gunakan seeder yang sudah disediakan (misalnya user default `admin@support.io`).

### 2. Panel Admin

Panel admin di-setup dengan `AdminPanelProvider`, contoh konfigurasi:

- ID: `admin`
- Path: `/admin`
- Layout: sidebar atau top navigation (`->topNavigation()`). [web:251]

Akses:

```text
http://127.0.0.1:8001/admin
```

Login menggunakan akun admin.

### 3. Panel Mechanic

Panel mekanik di-setup dengan `MechanicPanelProvider`, contoh konfigurasi:

- ID: `mechanic`
- Path: `/mechanic`;

Akses:

```text
http://127.0.0.1:8001/mechanic
```

Login menggunakan akun dengan role mechanic.

Hak akses ke masing-masing panel diatur di method `canAccessPanel` pada model `User`. [web:280][web:289]

---

## Menjalankan Aplikasi

Jalankan server pengembangan Laravel:

```bash
php artisan serve --port=8001
```

Kemudian buka di browser:

- Panel Admin: `http://127.0.0.1:8001/admin`
- Panel Mechanic: `http://127.0.0.1:8001/mechanic` [web:277]

---

## Command Penting Selama Development

Beberapa command yang sering digunakan saat mengembangkan aplikasi ini:

```bash
# Membersihkan cache config, route, view, dll
php artisan optimize:clear

# Menjalankan migration dari awal + seeding (hati-hati, akan drop semua tabel)
php artisan migrate:fresh --seed

# Regenerasi autoload composer
composer dump-autoload
```
[web:277][web:290]

---

## Struktur Fitur (Garis Besar)

- `Customer` Resource:
  - CRUD data pelanggan.
- `Motor` Resource:
  - CRUD data motor terkait pelanggan.
- `Sparepart` Resource:
  - CRUD data sparepart, stok dan harga.
- `Booking` Resource:
  - Pencatatan booking servis, relasi ke customer, motor, dan mechanic.
- `Purchase` Resource:
  - Pencatatan pembelian sparepart untuk kebutuhan stok.
- Dashboard:
  - Ringkasan data (misalnya jumlah booking, stok menipis, dsb).
  - Widget yang relevan untuk Admin dan Mechanic. [web:281][web:288]

---

## Catatan

- Project ini menggunakan Filament Panel sehingga hampir semua halaman admin dan mechanic dikelola via **Filament Resources** dan **Widgets**. [web:281][web:289]
- Jika ingin menambah menu baru, cukup membuat Resource baru dengan command:
  
  ```bash
  php artisan make:filament-resource NamaModel --generate
  ```

  lalu sesuaikan form, table, dan navigation icon-nya (`protected static ?string $navigationIcon`). [web:280][web:283]

# link untuk menyelamatkan mu

https://www.perplexity.ai/search/cara-cek-version-php-yang-ada-NgQ2n6JhTTK7t7LuZBxOBw#58