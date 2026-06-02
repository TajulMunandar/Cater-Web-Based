# RPD — Rencana Pengembangan Aplikasi Manajemen Pelanggan

**Tanggal Audit**: 26 Mei 2026
**Auditor**: Senior Laravel Developer & QA Engineer
**Aplikasi**: Cater Web-Based Tirta Pase — Manajemen Pelanggan

---

## DAFTAR ISI

1. [Langkah 1 — File yang Diaudit](#langkah-1--file-yang-diaudit)
2. [Langkah 2 — Audit Fitur](#langkah-2--audit-fitur)
3. [Langkah 3 — Temuan Bug & Mismatch](#langkah-3--temuan-bug--mismatch)
4. [Langkah 4 — RPD Baru](#langkah-4--rpd-baru)
5. [Langkah 5 — Ringkasan Eksekutif](#langkah-5--ringkasan-eksekutif)

---

## LANGKAH 1 — FILE YANG DIAUDIT

### Controllers
| File | Status |
|------|--------|
| `app/Http/Controllers/PelangganBaruController.php` | Dibaca ✅ |
| `app/Http/Controllers/PelangganController.php` | Dibaca ✅ |
| `app/Http/Controllers/PetaPelangganController.php` | Dibaca ✅ |
| `app/Http/Controllers/DsmlPelangganController.php` | Dibaca ✅ |

### Models
| File | Status |
|------|--------|
| `app/Models/Pelanggan.php` | Dibaca ✅ |
| `app/Models/PelangganDetail.php` | Dibaca ✅ |
| `app/Models/FotoPelanggan.php` | Dibaca ✅ |
| `app/Models/Wilayah.php` | Dibaca ✅ |
| `app/Models/Petugas.php` | Dibaca ✅ |
| `app/Models/Golongan.php` | Dibaca ✅ |
| `app/Models/KondisiMeter.php` | Dibaca ✅ |
| `app/Models/User.php` | Dibaca ✅ |

### Migrations
| File | Status |
|------|--------|
| `2025_04_15_064980_create_pelanggans_table.php` | Dibaca ✅ |
| `2025_04_15_065033_create_pelanggan_details_table.php` | Dibaca ✅ |
| `2025_04_15_064953_create_wilayahs_table.php` | Dibaca ✅ |
| `2025_04_15_065141_create_foto_pelanggans_table.php` | Dibaca ✅ |
| `2025_04_14_093542_create_petugas_table.php` | Dibaca ✅ |
| `2025_04_15_000000_create_golongans_table.php` | Dibaca ✅ |
| `2025_04_15_064943_create_kondisi_meters_table.php` | Dibaca ✅ |
| `0001_01_01_000000_create_users_table.php` | Dibaca ✅ |
| `2026_01_12_031715_migrate_petugas_to_users_table.php` | Dibaca ✅ |
| `2026_05_26_000001_add_id_wilayah_to_pelanggan_details_table.php` | Dibaca ✅ |
| `2026_05_26_000002_make_ket_nullable_in_pelanggan_details_table.php` | Dibaca ✅ |

### Routes
| File | Status |
|------|--------|
| `routes/web.php` | Dibaca ✅ |
| `routes/api.php` | File tidak ada ⚠️ |

### Views
| File | Status |
|------|--------|
| `dashboard/pages/pelanggan/pelanggan.blade.php` | Dibaca ✅ |
| `dashboard/pages/pelanggan/pelanggan-baru.blade.php` | Dibaca ✅ |
| `dashboard/pages/pelanggan/show.blade.php` | Dibaca ✅ |
| `dashboard/pages/data-pelanggan/create.blade.php` | Dibaca ✅ |
| `dashboard/pages/data-pelanggan/edit.blade.php` | Dibaca ✅ |
| `dashboard/partials/head.blade.php` | Dibaca ✅ |
| `dashboard/partials/script.blade.php` | Dibaca ✅ |
| `dashboard/component/modal.blade.php` | Dibaca ✅ |

### Lainnya
| File | Status |
|------|--------|
| `app/Providers/AppServiceProvider.php` | Dibaca ✅ |
| `tests/Feature/PelangganBaruTest.php` | Dibaca ✅ |

---

## LANGKAH 2 — AUDIT FITUR

### A. CRUD PELANGGAN

| Fitur | Status | File & Baris | Bukti |
|-------|--------|-------------|-------|
| Create — form, validasi, simpan ke DB | **✓ SELESAI** | `create.blade.php:35` `PelangganBaruController.php:104-125` | Form lengkap (nama, alamat, no_sambu, no_kontrol, telepon, type, wilayah, golongan, status, petugas, rute, kondisi, waktu_catat, stand, ket, urutan, wilayah_operasional, lat, long, foto). Validasi komprehensif. DB::transaction() |
| Read/list — DataTables AJAX | **✓ SELESAI** | `pelanggan-baru.blade.php:92-113` `PelangganBaruController.php:33-83` | ServerSide, 8 kolom (index, nama, alamat, no_sambu, no_kontrol, golongan, status_badge, action), responsive |
| Show/detail — endpoint & tampilan | **⚠ SEBAGIAN** | `PelangganBaruController.php:197-209` `show.blade.php` | Ada view, tapi ada 2 bug: (1) property Kondisi->nama salah, (2) navigasi kembali tidak sesuai asal |
| Update — form edit, validasi, simpan | **✓ SELESAI** | `PelangganBaruController.php:238-346` `edit.blade.php` | Full form, prefill semua field, DB::transaction(), foto handling (hapus lama + upload baru) |
| Delete — konfirmasi UI, hapus data | **✓ SELESAI** | `PelangganBaruController.php:351-394` `pelanggan-baru.blade.php:57-78` | Hard delete + cleanup foto dari storage, modal konfirmasi Bootstrap |

### B. RELASI & DATA PENDUKUNG

| Fitur | Status | File & Baris | Bukti |
|-------|--------|-------------|-------|
| Relasi Pelanggan → PelangganDetail | **✓ SELESAI** | `Pelanggan.php:29-31` | hasMany(id_pelanggan) dengan foreign key constraint restrict |
| Kolom id_wilayah di pelanggan_details | **✓ SELESAI** | Migration 2026_05_26_000001 `PelangganBaruController.php:153,298` `edit.blade.php:179` | Ada di DB, controller, dan form edit |
| $fillable di semua model | **⚠ SEBAGIAN** | `Pelanggan.php:12` (fillable), `PelangganDetail.php:12`, `FotoPelanggan.php:12`, `Wilayah.php:12`, `Petugas.php:12`, `Golongan.php:12`, `KondisiMeter.php:12` (guarded) | Hanya Pelanggan pakai fillable; 6 model lain masih guarded |
| Foreign key constraints | **✓ SELESAI** | Semua migration | Semua FK terdefinisi: onDelete('restrict'/'set null'/'cascade'), onUpdate('cascade') |

### C. UPLOAD & MANAJEMEN FOTO

| Fitur | Status | File & Baris | Bukti |
|-------|--------|-------------|-------|
| Upload foto saat create | **✓ SELESAI** | `PelangganBaruController.php:156-166` | Loop file, store ke 'pelanggan-foto' disk public, simpan path ke DB |
| Preview foto sebelum upload | **✓ SELESAI** | `create.blade.php:268-291` `edit.blade.php:303-326` | JS FileReader, validasi client-side size & format |
| Tampil di halaman detail/edit | **✓ SELESAI** | `show.blade.php:142-157` `edit.blade.php:205-228` | Grid card dengan gambar |
| Replace/delete foto saat update | **✓ SELESAI** | `PelangganBaruController.php:298-319` | Hapus file lama dari storage, simpan file baru |
| Hapus foto (tanpa hapus pelanggan) | **✓ SELESAI** | `PelangganBaruController.php:298-307` `edit.blade.php:216` | Checkbox "Hapus" per foto di form edit |
| Validasi server-side ukuran & format | **✓ SELESAI** | `PelangganBaruController.php:124,262` | mimes:jpeg,png,jpg,gif, max:2048 |

### D. PETA (MAP) & KOORDINAT

| Fitur | Status | File & Baris | Bukti |
|-------|--------|-------------|-------|
| Peta tampil di form create | **✓ SELESAI** | `create.blade.php:246-266` | Leaflet.js, setView ke koordinat default Aceh |
| Pin bisa diklik untuk set lokasi | **✓ SELESAI** | `create.blade.php:254-266` | `map.on('click', ...)` update lat/long input + marker |
| Koordinat tersimpan ke DB | **✓ SELESAI** | `PelangganBaruController.php:140-141` | lat & long dari validatedData |
| Peta di form edit — prefill koordinat | **✓ SELESAI** | `edit.blade.php:262-301` | Marker otomatis di posisi lat/long tersimpan |
| Marker bisa digeser (drag) | **✓ SELESAI** | `edit.blade.php:271,276-281` | `draggable: true`, event dragend update input |
| Koordinat terupdate saat edit | **✓ SELESAI** | `PelangganBaruController.php:279-280` | lat & long ikut diupdate |

### E. KEAMANAN & KUALITAS KODE

| Fitur | Status | File & Baris | Bukti |
|-------|--------|-------------|-------|
| Tidak ada `$request->all()` tanpa filter | **✓ SELESAI** | `PelangganController.php:77`, `PelangganBaruController.php:130` | Semua pakai $validated dari $request->validate() |
| Semua input divalidasi | **✓ SELESAI** | `PelangganBaruController.php:104-125, 242-264` | Form Request via `$request->validate()` |
| DB::transaction() untuk multi-tabel | **✓ SELESAI** | `PelangganBaruController.php:127, 266, 355` | BEGIN/COMMIT/ROLLBACK di store, update, destroy |
| QueryException ditangkap & dipetakan | **✓ SELESAI** | `PelangganBaruController.php:171, 325, 375` | Error code mapping (1062, 1451, 1452) ke pesan Bahasa Indonesia |
| try/catch di semua method DB | **✓ SELESAI** | `PelangganBaruController.php` semua method | QueryException + Exception catch |
| Method `data()` tanpa try/catch | **✗ ADA BUG** | `PelangganBaruController.php:33`, `PelangganController.php:31` | Tidak ada try/catch → HTTP 500 tanpa pesan jika error |

### F. FRONTEND & UX

| Fitur | Status | File & Baris | Bukti |
|-------|--------|-------------|-------|
| DataTables responsive | **⚠ SEBAGIAN** | `pelanggan-baru.blade.php:95` ✅ `pelanggan.blade.php:226` ❌ | pelanggan-baru sudah responsive; pelanggan utama masih `scrollX` saja |
| Loading indicator / spinner | **✗ BELUM ADA** | — | Tidak ada spinner untuk AJAX atau upload foto |
| Flash message setelah CRUD | **✓ SELESAI** | Semua view | success/error/delete/update flash messages |
| Form edit prefill semua field | **✓ SELESAI** | `edit.blade.php` | Semua field pakai `old('field', $data->field)` |
| Konfirmasi sebelum delete | **✓ SELESAI** | `pelanggan-baru.blade.php:57-78` | Modal Bootstrap + form DELETE |

### G. ROUTES & API

| Fitur | Status | File & Baris | Bukti |
|-------|--------|-------------|-------|
| Semua route CRUD terdaftar | **✓ SELESAI** | `web.php:34-40` | GET|POST|PUT|DELETE lengkap |
| Tidak ada route ke method kosong | **✓ SELESAI** | Semua | Semua method di PelangganBaruController sudah diimplementasi |
| Route penamaan konsisten | **✓ SELESAI** | `web.php:34-40` | Prefix `pelanggan.baru.*` konsisten |

---

## LANGKAH 3 — TEMUAN BUG & MISMATCH

### TEMUAN 1 — Property `nama` tidak ada di model KondisiMeter
| Item | Detail |
|------|--------|
| **Deskripsi** | Halaman detail pelanggan (`show.blade.php`) mengakses `$detail->Kondisi->nama`, tapi model `KondisiMeter` hanya punya atribut `kondisi` dan `kode`, bukan `nama`. |
| **File & baris** | `resources/views/dashboard/pages/pelanggan/show.blade.php:105` |
| **Kode bermasalah** | `$detail->Kondisi ? $detail->Kondisi->nama : '-'` |
| **Dampak** | **Data salah tampil** — field "Kondisi Meter" selalu menampilkan nilai kosong/null |
| **Rekomendasi** | Ganti `->nama` menjadi `->kondisi` |

### TEMUAN 2 — Inkonsistensi path foto
| Item | Detail |
|------|--------|
| **Deskripsi** | `show.blade.php` menggunakan `asset('storage/' . $foto->foto)`, sedangkan `edit.blade.php` menggunakan `Storage::url($foto->foto)`. Keduanya bisa berbeda hasil. |
| **File & baris** | `show.blade.php:149`, `edit.blade.php:213` |
| **Kode bermasalah** | `show`: `src="{{ asset('storage/' . $foto->foto) }}"` ; `edit`: `src="{{ \Illuminate\Support\Facades\Storage::url($foto->foto) }}"` |
| **Dampak** | Foto mungkin tidak tampil di salah satu halaman jika konfigurasi symlink berbeda. **UX buruk** |
| **Rekomendasi** | Konsisten menggunakan `Storage::url()` di kedua tempat |

### TEMUAN 3 — Navigasi "Kembali" tidak sesuai asal
| Item | Detail |
|------|--------|
| **Deskripsi** | Tombol "Kembali" di `show.blade.php` selalu mengarah ke `route('pelanggan.index')`. Jika user datang dari halaman "Pelanggan Baru" (`PelangganBaruController@show`), navigasi kembali akan salah. |
| **File & baris** | `show.blade.php:28` |
| **Kode bermasalah** | `<a href="{{ route('pelanggan.index') }}"` |
| **Dampak** | User dari tab "Data Pelanggan Baru" dikembalikan ke tab "Data Pelanggan". **UX buruk** |
| **Rekomendasi** | Gunakan `url()->previous()` atau passing parameter `back_route` ke view |

### TEMUAN 4 — Method `data()` tanpa error handling
| Item | Detail |
|------|--------|
| **Deskripsi** | Method `data()` di kedua controller (PelangganBaru & Pelanggan) tidak memiliki try/catch. Jika query database gagal, user mendapat HTTP 500 tanpa pesan. |
| **File & baris** | `PelangganBaruController.php:33-83`, `PelangganController.php:31-55` |
| **Kode bermasalah** | Tidak ada try/catch wrapper |
| **Dampak** | Error 500 tanpa pesan user-friendly. **Stabilitas aplikasi terganggu** |
| **Rekomendasi** | Bungkus query dalam try/catch, return JSON error response jika gagal |

### TEMUAN 5 — 6 model masih menggunakan `$guarded` (blacklist)
| Item | Detail |
|------|--------|
| **Deskripsi** | Model PelangganDetail, FotoPelanggan, Wilayah, Petugas, Golongan, KondisiMeter menggunakan `$guarded = ['id']`. Praktik blacklist kurang aman dibanding whitelist `$fillable`. |
| **File & baris** | Masing-masing model ~ baris 12 |
| **Kode bermasalah** | `protected $guarded = ['id'];` |
| **Dampak** | **Security risk** — jika ada field baru ditambahkan ke tabel migrasi, field tersebut bisa diisi tanpa filter |
| **Rekomendasi** | Konversi semua `$guarded` ke `$fillable` dengan daftar field eksplisit |

### TEMUAN 6 — DataTables pelanggan utama tidak responsive
| Item | Detail |
|------|--------|
| **Deskripsi** | DataTables di halaman "Data Pelanggan" (`pelanggan.blade.php`) belum mengaktifkan plugin Responsive. Halaman "Data Pelanggan Baru" sudah benar. |
| **File & baris** | `pelanggan.blade.php:226` |
| **Kode bermasalah** | `"scrollX": isMobile,` (tanpa `responsive: true`) |
| **Dampak** | **UX buruk** di perangkat mobile untuk halaman utama Data Pelanggan |
| **Rekomendasi** | Tambah `responsive: true`, hapus `scrollX` |

### TEMUAN 7 — Modal create tidak memiliki field `type`, `lat`, `long`
| Item | Detail |
|------|--------|
| **Deskripsi** | Modal create di halaman `pelanggan.blade.php` (untuk PelangganController) tidak memiliki input untuk `type`, `lat`, `long`, padahal controller memvalidasi field-field tersebut. |
| **File & baris** | `pelanggan.blade.php:81-133` |
| **Kode bermasalah** | Modal tidak memiliki `<input name="type">`, `<input name="lat">`, `<input name="long">` |
| **Dampak** | Data `type`, `lat`, `long` tidak bisa diisi via modal. **Fungsionalitas create terbatas** |
| **Rekomendasi** | Tambah field yang hilang ke modal atau arahkan ke halaman create lengkap |

### TEMUAN 8 — Update tidak membuat PelangganDetail baru jika belum ada
| Item | Detail |
|------|--------|
| **Deskripsi** | Method `update()` di PelangganBaruController hanya mengupdate PelangganDetail jika sudah ada (`if ($detail)`). Jika pelanggan dibuat tanpa detail (via PelangganController), update tidak akan membuat detail baru. |
| **File & baris** | `PelangganBaruController.php:283-296` |
| **Kode bermasalah** | `$detail = $pelanggan->PelangganDetail()->first(); if ($detail) { $detail->update(...); }` |
| **Dampak** | **Data loss potensial** — data operasional (petugas, rute, kondisi, dll) tidak tersimpan saat update jika belum ada sebelumnya |
| **Rekomendasi** | Jika `$detail` null, buat record baru dengan `$pelanggan->PelangganDetail()->create(...)` |

### TEMUAN 9 — Tidak ada loading indicator
| Item | Detail |
|------|--------|
| **Deskripsi** | Tidak ada spinner atau indikator visual untuk operasi AJAX DataTables (meskipun `processing: true` ada) atau upload foto. |
| **File & baris** | Semua view |
| **Dampak** | User tidak mendapat feedback visual saat proses后台 berjalan. **UX buruk** |
| **Rekomendasi** | Tambahkan loading overlay untuk operasi AJAX dan progress bar untuk upload |

---

## LANGKAH 4 — RPD BARU

### PRIORITAS P1 — WAJIB SELESAI DULU

#### [P1-01] Fix property `nama` → `kondisi` di show.blade.php
- **Deskripsi**: Halaman detail pelanggan menampilkan field "Kondisi Meter" kosong karena mengakses property `nama` yang tidak ada di model `KondisiMeter`. Ganti dengan `kondisi`.
- **Cara**: Ubah `$detail->Kondisi->nama` menjadi `$detail->Kondisi->kondisi`
- **Perubahan spesifik**:
  ```
  show.blade.php:105
  - $detail->Kondisi ? $detail->Kondisi->nama : '-'
  + $detail->Kondisi ? $detail->Kondisi->kondisi : '-'
  ```
- **File**: `resources/views/dashboard/pages/pelanggan/show.blade.php:105`
- **Estimasi**: 0.1 jam
- **Alasan P1**: Data salah tampil — user melihat field kosong tanpa tahu data sebenarnya.

#### [P1-02] Perbaiki update() agar buat PelangganDetail baru jika belum ada
- **Deskripsi**: Method `update()` hanya mengupdate PelangganDetail jika sudah ada. Jika pelanggan dibuat tanpa detail (via PelangganController), data operasional hilang. Tambah logic untuk create jika detail null.
- **Perubahan spesifik**:
  ```php
  PelangganBaruController.php:283-296
  - $detail = $pelanggan->PelangganDetail()->first();
  - if ($detail) { $detail->update([...]); }
  + $detail = $pelanggan->PelangganDetail()->first();
  + $detailData = [ ... ];
  + if ($detail) {
  +     $detail->update($detailData);
  + } else {
  +     $pelanggan->PelangganDetail()->create($detailData);
  + }
  ```
- **File**: `app/Http/Controllers/PelangganBaruController.php:283-296`
- **Estimasi**: 0.5 jam
- **Alasan P1**: Data loss potensial — update bisa menghilangkan data operasional pelanggan.

#### [P1-03] Konversi `$guarded` ke `$fillable` di semua model
- **Deskripsi**: 6 model masih menggunakan `$guarded = ['id']` (blacklist). Ganti ke `$fillable` (whitelist) untuk keamanan mass-assignment.
- **File yang diubah**:
  | Model | Field fillable |
  |-------|---------------|
  | `PelangganDetail` | id_pelanggan, id_petugas, rute, id_kondisi, waktu_catat_meter, stand_terakhir, ket, urutan, id_wilayah |
  | `FotoPelanggan` | id_pelanggan, foto |
  | `Wilayah` | wilayah, kode, ket, cabang, center_lat, center_long |
  | `Petugas` | photo, nama, nip, no_hp1, no_hp2, tipe_pekerjaan, level, jenis_pekerjaan, user_id |
  | `Golongan` | kode, nama, tarif_per_m3, biaya_admin, keterangan |
  | `KondisiMeter` | kondisi, keterangan, kode |
- **Estimasi**: 1 jam
- **Alasan P1**: Security risk — field baru yang ditambahkan ke tabel bisa diisi tanpa filter.

#### [P1-04] Tambah try/catch di method `data()` kedua controller
- **Deskripsi**: Method `data()` di PelangganBaruController dan PelangganController tidak memiliki error handling. Jika query gagal, user mendapat 500 error tanpa pesan.
- **Perubahan spesifik**:
  ```php
  PelangganBaruController.php:33-83
  PelangganController.php:31-55
  + try {
        $pelanggans = Pelanggan::with(...)...;
        return DataTables::of($pelanggans)...->make(true);
  + } catch (\Exception $e) {
  +     Log::error('DataTables error: ' . $e->getMessage());
  +     return response()->json(['error' => 'Gagal memuat data pelanggan.'], 500);
  + }
  ```
- **File**: `app/Http/Controllers/PelangganBaruController.php:33-83`, `app/Http/Controllers/PelangganController.php:31-55`
- **Estimasi**: 0.5 jam
- **Alasan P1**: Stabilitas aplikasi — error 500 tanpa pesan jika database bermasalah.

---

### PRIORITAS P2 — FUNGSIONAL & UX

#### [P2-01] Konsistenkan path foto pakai `Storage::url()`
- **Deskripsi**: Ubah `asset('storage/' . $foto->foto)` menjadi `\Illuminate\Support\Facades\Storage::url($foto->foto)` di show.blade.php
- **File**: `resources/views/dashboard/pages/pelanggan/show.blade.php:149`
- **Estimasi**: 0.1 jam
- **Alasan**: Menjamin foto tampil konsisten di semua environment.

#### [P2-02] Aktifkan DataTables responsive di pelanggan.blade.php
- **Deskripsi**: Ganti `"scrollX": isMobile` dengan `responsive: true`. Hapus variabel `isMobile` yang sudah tidak dipakai.
- **File**: `resources/views/dashboard/pages/pelanggan/pelanggan.blade.php:204,226`
- **Estimasi**: 0.1 jam
- **Alasan**: UX mobile — halaman utama Data Pelanggan belum responsive.

#### [P2-03] Perbaiki navigasi "Kembali" di show.blade.php
- **Deskripsi**: Gunakan `url()->previous()` agar tombol "Kembali" mengarah ke halaman asal user.
- **Perubahan spesifik**:
  ```php
  show.blade.php:28
  - <a href="{{ route('pelanggan.index') }}" ...>Kembali</a>
  + <a href="{{ url()->previous() }}" ...>Kembali</a>
  ```
- **File**: `resources/views/dashboard/pages/pelanggan/show.blade.php:28`
- **Estimasi**: 0.3 jam
- **Alasan**: UX — user dari tab "Pelanggan Baru" perlu kembali ke tab yang benar.

#### [P2-04] Lengkapi field modal create di pelanggan.blade.php
- **Deskripsi**: Tambah input field `type`, `lat`, `long` ke modal create, atau ganti dengan redirect ke halaman create lengkap (`pelanggan.baru.create`).
- **Opsi A (rekomendasi)**: Redirect tombol "Tambah" ke `route('pelanggan.baru.create')` dan hapus modal create.
- **Opsi B**: Tambah field yang hilang ke modal.
- **File**: `resources/views/dashboard/pages/pelanggan/pelanggan.blade.php:81-133`
- **Estimasi**: 0.5 jam
- **Alasan**: Fungsionalitas create terbatas — field `type`, `lat`, `long` tidak bisa diisi.

#### [P2-05] Tambah loading indicator untuk upload foto & AJAX
- **Deskripsi**: Tambah spinner/overlay saat upload foto di form create/edit, dan saat AJAX DataTables berproses.
- **File**: `create.blade.php`, `edit.blade.php`, `pelanggan-baru.blade.php`
- **Estimasi**: 0.5 jam
- **Alasan**: UX — user perlu feedback visual saat proses berjalan.

---

### PRIORITAS P3 — KUALITAS & MAINTAINABILITY

#### [P3-01] Implementasi soft delete untuk Pelanggan
- **Deskripsi**: Tambah kolom `deleted_at` ke tabel `pelanggans`, gunakan `SoftDeletes` trait di model. Ubah destroy() jadi soft delete. Tambah filter di DataTables untuk hanya menampilkan data aktif.
- **File**: Migration baru, `app/Models/Pelanggan.php`, `app/Http/Controllers/PelangganBaruController.php`
- **Estimasi**: 2 jam
- **Alasan**: Maintainability — hard delete menyebabkan data hilang permanen.

#### [P3-02] Timezone handling untuk input datetime-local
- **Deskripsi**: Input `waktu_catat_meter` dari form datetime-local dalam zona waktu lokal (WIB, UTC+7). Saat ini disimpan langsung tanpa konversi ke UTC. Tambah accessor/mutator atau cast untuk konversi.
- **File**: `app/Models/PelangganDetail.php`, `app/Http/Controllers/PelangganBaruController.php`
- **Estimasi**: 1 jam
- **Alasan**: Konsistensi data — mencegah selisih waktu antara input dan penyimpanan.

#### [P3-03] Unit test untuk PelangganController (non-baru)
- **Deskripsi**: Buat test untuk store() dan show() di PelangganController.
- **File**: `tests/Feature/PelangganTest.php` (baru)
- **Estimasi**: 1.5 jam
- **Alasan**: Kualitas — memastikan PelangganController tidak error.

#### [P3-04] Replace enum migration dengan string + validation rule
- **Deskripsi**: Migration `pelanggans` menggunakan `$table->enum('status', ['aktif', 'non-aktif'])` yang tidak portabel. Ganti ke `$table->string('status', 20)` dengan validasi `in:aktif,non-aktif` di controller.
- **File**: Migration baru, validasi sudah ada di controller.
- **Estimasi**: 1 jam
- **Alasan**: Portabilitas — enum MySQL hanya work di MySQL.

---

## LANGKAH 5 — RINGKASAN EKSEKUTIF

### Persentase Fitur Selesai: ~85%

Semua fitur CRUD inti sudah berfungsi penuh:
- ✅ Create/Read/Update/Delete pelanggan
- ✅ Upload & manajemen foto
- ✅ Peta interaktif dengan Leaflet.js
- ✅ DataTables server-side dengan AJAX
- ✅ Validasi input (client & server)
- ✅ Error handling database dengan pesan Bahasa Indonesia
- ✅ 8 unit test (semua lolos)

### 3 Hal Paling Kritis (Prioritas P1):

1. **P1-01 — Fix data Kondisi Meter** (0.1 jam): Property `nama` tidak ada di model `KondisiMeter`, menyebabkan field Kondisi Meter selalu kosong di halaman detail. Perbaikan: ganti `->nama` jadi `->kondisi`.

2. **P1-02 — Buat PelangganDetail baru saat update jika belum ada** (0.5 jam): Method `update()` hanya mengupdate detail yang sudah ada. Jika pelanggan dibuat tanpa detail (via PelangganController), data operasional hilang saat update. Perbaikan: tambah logic `create()` jika detail null.

3. **P1-03 — Konversi $guarded ke $fillable di 6 model** (1 jam): Model PelangganDetail, FotoPelanggan, Wilayah, Petugas, Golongan, KondisiMeter masih pakai blacklist `$guarded`. Ini membuka celah mass-assignment. Perbaikan: daftar field eksplisit di `$fillable`.

### Estimasi Total Pengerjaan

| Prioritas | Jumlah Task | Estimasi Waktu |
|-----------|-------------|----------------|
| P1 | 4 task | 2.1 jam |
| P2 | 5 task | 1.5 jam |
| P3 | 4 task | 5.5 jam |
| **Total** | **13 task** | **9.1 jam** |

### Catatan Tambahan

- **Tidak ada issue keamanan kritis** — semua input sudah divalidasi, tidak ada `$request->all()` tanpa filter, transaksi database sudah benar.
- **Kualitas kode baik** — pola Laravel diikuti dengan benar (repository-less, langsung controller ke model), naming convention konsisten.
- **Testing coverage terbatas** — hanya PelangganBaru yang punya test (8 test). PelangganController dan controller lain belum ada test.
- **Perbaikan yang sudah dilakukan** (dari sesi sebelumnya): implementasi show/update/destroy, responsive DataTables, error handling SQLSTATE, mass-assignment fix di PelangganController, model casts datetime.
