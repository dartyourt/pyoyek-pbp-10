# UMKM Mini Commerce

Aplikasi E-Commerce sederhana untuk UMKM (Usaha Mikro Kecil Menengah) yang dibangun menggunakan Laravel 12, Breeze, dan Tailwind CSS.

## Persyaratan Sistem

- PHP 8.2 atau lebih tinggi
- Composer
- MySQL
- Node.js dan NPM

## Teknologi

- Laravel 12
- Tailwind CSS
- Breeze untuk autentikasi
- MySQL
- AlpineJS

## Langkah Instalasi

Berikut adalah langkah-langkah untuk menginstall dan menjalankan aplikasi UMKM Mini Commerce:

### 1. Clone Repository

```bash
git clone https://github.com/dartyourt/pyoyek-pbp-10.git
cd pyoyek-pbp-10
```

### 2. Install Dependensi PHP

```bash
composer install
```

### 3. Install Dependensi JavaScript

```bash
npm install
npm run build
```

### 4. Persiapkan Environment

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Kemudian, edit file `.env` untuk mengkonfigurasi koneksi database:

```
APP_NAME="UMKM Mini Commerce"
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=umkm_mini
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generate App Key

```bash
php artisan key:generate
```

### 6. Jalankan Migrasi Database

```bash
php artisan migrate
```

### 7. (Opsional) Jalankan Seeder untuk Data Demo

```bash
php artisan db:seed
```

### 8. Jalankan Aplikasi

```bash

```

Setelah itu, aplikasi dapat diakses melalui `http://localhost:8000`

## Fitur Aplikasi

- Autentikasi Pengguna (Login/Register)
- Manajemen Produk
- Manajemen Kategori
- Keranjang Belanja
- Proses Checkout
- Manajemen Pesanan
- Dashboard Admin

## Akun Default

Administrator:
- Email: admin@example.com
- Password: password

Pengguna:
- Email: user@example.com
- Password: password

## Struktur Database

Aplikasi ini menggunakan beberapa tabel utama:

- `users` - Menyimpan data pengguna dengan role
- `categories` - Kategori produk
- `products` - Produk yang dijual
- `carts` - Keranjang belanja pengguna
- `cart_items` - Item dalam keranjang belanja
- `orders` - Pesanan yang dibuat
- `order_items` - Item dalam pesanan

## Kontribusi

Kontribusi dan saran sangat diterima. Silakan buat issue atau pull request jika Anda ingin berkontribusi pada project ini.

## Struktur Proyek

```
app/
  ├── Http/
  │   ├── Controllers/   # Controller aplikasi
  │   │   ├── Admin/     # Controller khusus admin
  │   │   └── Catalog/   # Controller katalog produk
  │   └── Requests/      # Form requests untuk validasi
  ├── Models/            # Model Eloquent
  └── Providers/         # Service providers
  
database/
  ├── migrations/        # Migrasi database
  └── seeders/          # Seeder untuk data awal
  
public/                 # Asset publik
  
resources/
  ├── css/              # File CSS (Tailwind)
  ├── js/               # File JavaScript
  └── views/            # Blade templates
      ├── admin/        # View untuk admin
      └── catalog/      # View untuk katalog
  
routes/
  └── web.php           # Definisi route
```

