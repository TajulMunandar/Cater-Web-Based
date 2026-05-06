# Cater - Aplikasi Catat Meter Air

Aplikasi web berbasis Laravel untuk pengelolaan catat meter air, data pelanggan, dan pelaporan.

## Fitur

### 1. Dashboard
Halaman utama yang menampilkan overview sistem dengan statistik dan ringkasan data.

### 2. Catat Meter
Fitur untuk mencatat pembacaan meter air pelanggan.
- **Catat Meter**: Input data pembacaan meter (pelanggan, petugas, kondisi, stand meter, GPS, status)
- **Catat Meter Tidak Terdaftar**: Pencatatan untuk pelanggan yang belum terdaftar
- **Urutan Catat Meter**: Pengurutan pekerjaan catat meter

### 3. Data Rekap
Menampilkan laporan dan rekap data.
- **Rekap Catat Meter**: Laporan data pembacaan meter dengan DataTables server-side
- **Rekap Wilayah**: Laporan berdasarkan wilayah
- **Rekap Kondisi**: Laporan berdasarkan kondisi meter

### 4. Pelanggan
Pengelolaan data pelanggan lengkap.
- **Data Pelanggan**: Daftar lengkap pelanggan dengan CRUD penuh (nama, alamat, no sambu, no kontrol, wilayah, golongan, status, koordinat GPS)
- **Data Pelanggan Baru**: Input pelanggan baru
- **Peta Pelanggan**: Visualisasi lokasi pelanggan di peta interaktif (Leaflet/OpenStreetMap) dengan popup detail
- **DSML**: Daftar Susunan Meter Listrik

### 5. Settings (Data Master)
Konfigurasi data master dengan validasi lengkap.
- **Wilayah**: Pengelolaan data wilayah (nama, kode, cabang, koordinat center)
- **Golongan**: Pengelolaan tarif dan biaya admin per kategori pelanggan
- **Kondisi**: Pengelolaan kondisi meter (kondisi, kode, keterangan)
- **Petugas**: Pengelolaan data petugas dengan integrasi User (nama, NIP, no HP, email, username, level, foto)

### 6. Info
Pengelolaan informasi dan berita terkait layanan dengan full CRUD.

### 7. Autentikasi & Autorisasi
Sistem login/register dengan role-based access control (RBAC):
- **Admin** (level=0): Akses penuh ke semua fitur
- **Petugas** (level=1): Akses terbatas sesuai tugas

## Teknologi

- Laravel 10+ (Framework)
- Bootstrap 5 (UI)
- DataTables dengan server-side processing
- Leaflet.js (Peta)
- Yajra DataTables
- Middleware untuk role-based access

## Instalasi

```bash
# Clone project
composer install

# Setup environment
cp .env.example .env

# Generate key
php artisan key:generate

# Jalankan migration
php artisan migrate

# Seed database (termasuk user admin/petugas, wilayah, kondisi, golongan)
php artisan db:seed

# Jalankan server
php artisan serve
```

### Akun Default Setelah Seeding:
- **Admin**: admin@example.com / password (level=0)
- **Petugas**: petugas@example.com / password (level=1)

## Struktur Database

### Tabel Utama:
- `users` - User untuk login (ada field `level` untuk role)
- `petugas` - Data petugas (relasi ke users)
- `pelanggans` - Data pelanggan (relasi ke wilayah, golongans)
- `golongans` - Golongan/tarif pelanggan
- `wilayahs` - Data wilayah
- `kondisi_meters` - Kondisi meter
- `catat_meters` - Data catat meter

### Relasi:
- Pelanggan belongsTo Wilayah, Golongan
- Petugas belongsTo User
- CatatMeter belongsTo Pelanggan, Petugas, KondisiMeter

## Best Practices yang Diterapkan

1. **Validasi**: Semua form menggunakan validation rules di Controller
2. **Error Handling**: Try-catch dengan logging ke file dan user feedback
3. **Security**: XSS protection dengan htmlspecialchars, CSRF protection
4. **Code Organization**: Logic di controller dirapikan, modal di-generate via PHP
5. **DataTables**: Server-side processing untuk performa optimal
6. **Middleware**: CheckRole untuk authorization berbasis level

## Lisensi

MIT