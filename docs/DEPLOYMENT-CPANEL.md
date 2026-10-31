# 📦 Panduan Deploy cPanel — Bambu Water Nusantara v3.0.0.0

> Untuk pemilik tanpa akses SSH/terminal. Semua langkah memakai **cPanel File Manager** dan browser.
> Estimasi waktu: **15–20 menit**. Tidak ada Node.js, npm, atau build tool — murni upload & klik.

---

## 1. Yang Anda butuhkan
| Kebutuhan | Cara cek |
|---|---|
| File `bambu-water-php-3.0.0.0.zip` | Dari paket rilis / hasil `git clone` + kompres folder |
| Login cPanel hosting Anda | dari penyedia hosting |
| Domain mengarah ke hosting (DNS) | cPanel → Domains → document root `public_html` |

**Isi ZIP = isi repo ini** (index.php, config.php, admin-cms.php, lib/, templates/, data/, assets/, tools/, tests/, docs/, .htaccess, README, dll).

---

## 2. Persiapan aman (backup site lama) — 3 menit
1. cPanel → **File Manager** → masuk `public_html`.
2. Jika ada situs lama: klik **Select All** → **Compress** → pilih **Zip** → beri nama `backup-lama-<tanggal>.zip` → unduh ke komputer Anda.
3. Simpan juga folder `data/` lama (kalau ada) — itulah database & pesan pelanggan.
> Rollback = kembali mengekstrak ZIP ini (lihat §9).

---

## 3. Upload & Ekstrak aplikasi — 5 menit
1. File Manager → `public_html` → tombol **Upload** → pilih `bambu-water-php-3.0.0.0.zip`.
2. Kembali ke File Manager → klik kanan ZIP → **Extract** → tujuan `/public_html` → **Extract File(s)**.
3. Hapus file ZIP-nya (opsional, kerapian).
4. Hasil akhir — struktur yang BENAR:
```
public_html/
├── index.php          ← titik masuk (wajib paling atas)
├── config.php
├── admin-cms.php
├── .htaccess          ← file tersembunyi: Settings → Show Hidden Files
├── lib/  templates/  tools/  tests/  docs/
├── data/              ← di sinilah SQLite dibuat otomatis
└── assets/ (css, js, images, fonts)
```
⚠️ **Jangan sampai** `index.php` berada di dalam subfolder ganda (`public_html/bambu-water-php-.../index.php` salah).

---

## 4. Pilih PHP 8.4 — 2 menit
1. cPanel → cari **"Select PHP Version"** atau **"MultiPHP Manager"**.
2. Pilih domain → set versi **PHP 8.4** (minimal 8.1; 8.4 = target resmi).
3. (Opsional, di Select PHP Version → Extensions) pastikan tercentang: `pdo_sqlite`, `sqlite3`, `mbstring`, `gd` — biasanya aktif default.

---

## 5. Izin folder (permission) — 1 menit
1. File Manager → klik kanan folder **`data/`** → **Change Permissions** → centang **Write** untuk Owner (755). Bila gagal menulis, naikkan menjadi 775.
2. File di dalamnya biarkan 644.

---

## 6. Verifikasi situs publik — 3 menit
Buka di browser (ganti domain Anda):

| URL | Hasil yang benar |
|---|---|
| `https://bambuwater.web.id/` | Beranda lengkap 14 section, gambar tampil |
| `https://bambuwater.web.id/kota/bandung` | Halaman kota (clean URL, tanpa `#`) |
| `https://bambuwater.web.id/kesehatan` | 60 kondisi kesehatan |
| `https://bambuwater.web.id/sitemap.xml` | XML index sitemap |
| `https://bambuwater.web.id/api/health` | `{"status":"healthy","db":"up"}` |
| `https://bambuwater.web.id/config.php` | **403 Forbidden** (proteksi bekerja) |
| `https://bambuwater.web.id/data/company.json` | **403 Forbidden** |
| URL salah, mis. `/zzz` | Halaman 404 rapi |

Jika semua halaman 404 kecuali beranda → minta hosting mengaktifkan **mod_rewrite** + **AllowOverride All**.

---

## 7. Login admin pertama (WAJIB ganti password) — 2 menit
1. Buka `https://bambuwater.web.id/admin`.
2. Login: `admin` / `bambuwater2025`.
3. Anda akan **langsung diarahkan ke menu Akun** — ini fitur keamanan: password default terkunci sampai diganti.
4. Isi password baru (min. 8 karakter, kuat) → **Ganti Password**.
5. Menu **Pengaturan**:
   - **URL Situs**: `https://bambuwater.web.id` (untuk canonical & sitemap).
   - **Email Notifikasi** (opsional): alamat Anda — setiap pesan masuk dikabari via email.
   - Simpan.

---

## 8. Checklist verifikasi akhir
- [ ] Beranda + 3 halaman contoh terbuka (kota/kesehatan/artikel)
- [ ] `/admin` ganti password berhasil, dashboard terbuka
- [ ] `config.php` dan `data/*.json` → 403
- [ ] `/sitemap.xml` memuat 2.649+ URL
- [ ] Form kontak mengirim pesan → muncul di Admin → Pesan
- [ ] (Opsional) Submit sitemap di Google Search Console / Bing Webmaster

---

## 9. Rollback (jika perlu) — < 5 menit
1. File Manager → `public_html` → hapus file/folder aplikasi baru (index.php, config.php, admin-cms.php, lib, templates, data, assets, tools, .htaccess, tests, docs).
2. Ekstrak `backup-lama-<tanggal>.zip` → selesai.
3. Restore database: kembalikan `data/cms.sqlite` (+ `-wal`/`-shm` bila ada) dari backup.

---

## 10. Pasca-deploy yang disarankan
1. **HTTPS**: cPanel → SSL/TLS Status → **Run AutoSSL**. Setelah sertifikat aktif, buka `.htaccess` → hapus tanda `#` pada blok *"Kanonisasi HTTPS"* (5 baris) → simpan. Semua http otomatis 301 ke https.
2. **Search Console**: verifikasi (meta sudah siap di config.php `SEO_GOOGLE_SITE_VERIFICATION`, atau file verifikasi) → submit `sitemap.xml`.
3. **IndexNow** (Bing/Yandex instant): dari terminal lokal `php tools/ping-indexnow.php https://bambuwater.web.id` — atau biarkan otomatis: setiap artikel disimpan di CMS langsung di-ping.
4. **Analitik** (opsional): isi `ANALYTICS_GA4_ID` / `ANALYTICS_CLARITY_ID` di `config.php` — pengunjung akan diminta persetujuan dulu (banner cookie otomatis).

---

## 11. Peta file — apa yang harus DIJAGA
| Path | Peran | Boleh dihapus? |
|---|---|---|
| `data/*.json` | Konten seluruh situs | ❌ JANGAN |
| `data/cms.sqlite` | Database (pesan, pengaturan, password) | ❌ JANGAN |
| `.htaccess` | Routing + keamanan | ❌ JANGAN |
| `assets/` | Tampilan & gambar | ❌ JANGAN |
| `docs/`, `tests/` | Dokumentasi & uji | Opsional (boleh tak di-upload; tidak dieksekusi web) |
| `data/cache/` | Cache sitemap/index | ✅ Boleh dihapus kapan pun (dibuat ulang) |
