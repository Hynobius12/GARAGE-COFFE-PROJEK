# ☕ Garage Coffee

Sistem manajemen kafe berbasis web: **E-Menu untuk pelanggan, POS untuk kasir, KDS untuk barista, serta dashboard, inventori, dan laporan untuk owner**. Dibangun dengan Laravel 11.

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3-06B6D4?logo=tailwindcss&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3-8BC0D0?logo=alpinedotjs&logoColor=white)

---

## ✨ Fitur Utama

### 🛒 Pelanggan (publik, tanpa login)
- **E-Menu** di `/menu`: daftar produk per kategori beserta varian.
- **Pesan & bayar via QRIS**: pelanggan mengisi nama, nomor meja/take away, lalu mengunggah **bukti pembayaran**.
- **Halaman status pesanan** (`/order/{kode}/status`) yang auto-refresh setiap 30 detik.

### 💵 Kasir (POS)
- Halaman kasir dengan keranjang, varian produk, diskon, dan catatan khusus per item.
- Metode pembayaran: **tunai, QRIS, transfer**.
- Riwayat transaksi, serta pembaruan status pesanan POS dan pesanan web dari satu layar.
- **Stok bahan baku berkurang otomatis** sesuai resep setiap kali transaksi POS dibuat.

### 🧑‍🍳 Barista (KDS)
- *Kitchen Display System* di `/barista/kds`, auto-refresh setiap 15 detik.
- Alur status: **pending → processing → completed**.

### 📊 Owner / Admin
- **Dashboard**: omzet hari ini (POS + web), jumlah pesanan, produk terlaris, grafik penjualan 7 hari, antrean bukti QRIS, dan peringatan stok menipis.
- **Manajemen produk & kategori**: CRUD, upload gambar, varian harga, dan soft delete.
- **Resep produk**: hubungkan produk dengan bahan baku beserta takaran per porsi.
- **Inventori bahan baku**: CRUD, SKU, minimum stok, dan riwayat transaksi stok (`stock_transactions`).
- **Verifikasi pesanan QRIS**: konfirmasi atau tolak pembayaran, proses, selesaikan, dan cetak struk.
- **Laporan penjualan** dengan filter tanggal, **ekspor Excel dan PDF**.

---

## 👥 Role & Hak Akses

Menggunakan [spatie/laravel-permission](https://github.com/spatie/laravel-permission).

| Role | Akses |
|------|-------|
| `owner` | Semua fitur admin, POS, KDS, dan manajemen QRIS |
| `cashier` | POS, riwayat transaksi, manajemen pesanan QRIS, cetak struk |
| `barista` | KDS dan manajemen pesanan QRIS |

Setelah login, user diarahkan otomatis ke halaman sesuai role-nya.

---

## 🧱 Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Backend | PHP 8.2+, Laravel 11 |
| Frontend | Blade, Tailwind CSS 3, Alpine.js, Vite 5 |
| Auth | Laravel Breeze |
| Role & permission | spatie/laravel-permission |
| Ekspor laporan | maatwebsite/excel, barryvdh/laravel-dompdf |
| Gambar | intervention/image |
| Database | MySQL / MariaDB (direkomendasikan, lihat catatan di bawah) |

---

## 🗂️ Struktur Proyek

```
app/
├── Console/Commands/      # garage:clean-old-data
├── Exports/               # SalesExport (Excel)
├── Http/Controllers/
│   ├── Admin/             # Dashboard, Produk, Kategori, Resep, Varian,
│   │                      # Bahan Baku, Order, QRIS, Laporan
│   ├── Barista/           # KDSController
│   ├── Cashier/           # POSController
│   └── Web/               # MenuController (E-Menu publik)
├── Models/                # Product, Category, Order, QrisOrder, RawMaterial, ...
└── Services/
    ├── OrderService.php       # Membuat order POS dalam satu DB transaction
    └── InventoryService.php   # Pengurangan stok berdasarkan resep
database/
├── migrations/
└── seeders/               # Role & user, kategori, produk, data demo
resources/views/           # admin/, cashier/, barista/, public/, auth/
routes/web.php
```

---

## 🚀 Instalasi

### Prasyarat
- PHP **8.2+** dengan ekstensi umum Laravel (`mbstring`, `gd`, `pdo_mysql`, dll.)
- Composer
- Node.js 18+ dan npm
- MySQL atau MariaDB

### Langkah

```bash
# 1. Clone repo
git clone https://github.com/<username>/<nama-repo>.git
cd <nama-repo>

# 2. Install dependency
composer install
npm install

# 3. Siapkan environment
cp .env.example .env
php artisan key:generate
```

Edit `.env` dan arahkan ke database MySQL kamu:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=garage_coffee
DB_USERNAME=root
DB_PASSWORD=
```

```bash
# 4. Migrasi dan seed data awal
php artisan migrate --seed

# 5. Link storage (untuk bukti pembayaran & gambar produk)
php artisan storage:link

# 6. Jalankan aplikasi
npm run dev          # terminal 1
php artisan serve    # terminal 2
```

Buka <http://localhost:8000>. Untuk build produksi, jalankan `npm run build`.

> ⚠️ **Gunakan MySQL/MariaDB.** Salah satu migrasi (`update_status_enum_on_qris_orders_table`) memakai perintah `ALTER TABLE ... MODIFY COLUMN ... ENUM`, yang tidak didukung SQLite.

---

## 🔑 Akun Demo

Dibuat oleh `RoleAndPermissionSeeder`:

| Role | Email | Password |
|------|-------|----------|
| Owner | `owner@garagecoffee.com` | `password` |
| Kasir | `kasir@garagecoffee.com` | `password` |
| Barista | `barista@garagecoffee.com` | `password` |



---

## 🔄 Alur Pesanan

**POS (kasir)**
```
Kasir input pesanan → stok bahan terpotong otomatis → pending → processing → completed
```

**Web / QRIS (pelanggan)**
```
pending_payment → payment_uploaded → confirmed → processing → completed
                                  ↘ cancelled (ditolak)
```

---

## 🗺️ Ringkasan Route

| Area | Prefix | Role |
|------|--------|------|
| E-Menu & QRIS | `/menu`, `/qris`, `/order/...` | Publik |
| Admin | `/admin/...` | `owner` |
| Manajemen QRIS | `/admin/qris-orders/...` | `owner`, `cashier`, `barista` |
| POS | `/cashier/pos` | `owner`, `cashier` |
| KDS | `/barista/kds` | `barista` |

Daftar lengkap bisa dilihat dengan `php artisan route:list`.

---

## 🧰 Perintah Artisan Tambahan

```bash
php artisan garage:clean-old-data
```
Menghapus kategori dan produk lama yang tidak termasuk daftar menu aktif (*Main Course, Lite Bites, Eskosu, Basic Coffee, Mixology, Variant*). **Bersifat destruktif**, jadi backup database dulu.

---

## 🧪 Testing

```bash
php artisan test
```

---

## 📸 Screenshot

menyusul

---

## 📄 Lisensi

Proyek ini menggunakan lisensi [MIT](https://opensource.org/licenses/MIT) (mengikuti Laravel).

---

<p align="center">Dibuat untuk cafe Garage Coffee☕</p>
