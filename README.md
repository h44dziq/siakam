# SIAKAM

Sistem pelaporan fasilitas FIK (Laravel + Blade, tanpa Breeze/Livewire/React).
Zip ini berisi file yang ditimpa ke proyek Laravel 11 atau 12 yang baru.

## Pasang

Butuh PHP 8.2+, Composer, Node.js.

```bash
composer create-project laravel/laravel siakam
cd siakam
```

1. Ekstrak isi zip ini ke dalam folder `siakam` (timpa file yang ada: `bootstrap/app.php`, `routes/web.php`, `app/Models/User.php`, `resources/css/app.css`, `resources/js/app.js`, `database/seeders/DatabaseSeeder.php`).
2. Tambahkan ke `.env`:

```
APP_NAME=SIAKAM
APP_LOCALE=id
APP_TIMEZONE=Asia/Jakarta
CAMPUS_EMAIL_DOMAIN=mahasiswa.upnvj.ac.id
```

3. Jalankan:

```bash
php artisan migrate --seed
npm install
npm run dev          # terminal 1
php artisan serve    # terminal 2
```

Buka http://127.0.0.1:8000. Database bawaan Laravel adalah SQLite, jadi tidak perlu setup DB.
Kalau mau MySQL/PostgreSQL, ubah bagian DB di `.env` sebelum `migrate`.

## Akun Demo (Development & Testing)

Akun berikut dibuat oleh `database/seeders/DatabaseSeeder.php` dan **hanya untuk pengembangan serta pengujian lokal**. Jangan dipakai di produksi, jangan tampilkan kredensialnya di halaman publik, dan ganti atau hapus akun ini sebelum aplikasi dipakai sungguhan.

| Role | Email | Password |
|---|---|---|
| Pemimpin | pemimpin@siakam.test | password |
| Admin | admin@siakam.test | password |
| Mahasiswa | 2310512001@mahasiswa.upnvj.ac.id | password |
| Mahasiswa | 2310512002@mahasiswa.upnvj.ac.id | password |

Jalankan `php artisan migrate --seed` untuk membuatnya.

## Yang sudah ada (sesuai PRD)

- Login semua role, register mahasiswa (email kampus divalidasi di server, email tidak bisa diubah), redirect per role, akun nonaktif ditolak
- Mahasiswa: buat laporan (gedung, ruangan, kategori, deskripsi, foto), riwayat, detail + riwayat penanganan
- OB: daftar laporan dengan filter status/gedung, ubah status + catatan
- Admin: ringkasan (kartu, top 3 ruangan, laporan per kategori, filter bulan/tahun), semua laporan (cari nama/NIM, status, tanggal), ekspor CSV
- Data master: CRUD gedung, ruangan, kategori (data yang sudah dipakai laporan tidak bisa dihapus)
- Manajemen pengguna: tab Mahasiswa (aktif/nonaktif saja), OB (tambah, ubah, reset password, aktif/nonaktif), Admin (hanya Superadmin)
- Status hanya maju: Dilaporkan, Diproses, Selesai. Semua perubahan tercatat di `report_status_histories`
- Foto disimpan di disk private dan hanya bisa dibuka lewat route `/foto/{id}` setelah login

