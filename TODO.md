# TODO: Perbaiki Spot Kendaraan - Kendaraan Masuk & Keluar

## Status: FIXED ✅

**Masalah:** Syntax error di ParkingController@store (missing $request->validate)

**Perbaikan:**
- Tambah `$` pada `$request->validate` 
- Controller sekarang valid PHP
- Form submit → create record → muncul di tabel "Kendaraan Sedang Parkir"
- Tombol Keluar → update exit_time + price → hilang dari tabel aktif

**Test Steps:**
1. `php artisan serve`
2. Login → http://localhost:8000/spots
3. Isi form: Plat `B123 ABC`, Jenis `Mobil` → Submit
4. Lihat di tabel
5. Klik `Keluar` → Total biaya muncul, kendaraan hilang dari tabel

**File Diperbaiki:**
- `app/Http/Controllers/ParkingController.php`

Fungsi lengkap! Kendaraan tercetak di tabel saat masuk dan bisa dikeluarkan.
