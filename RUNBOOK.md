# 🛠️ RUNBOOK Operasi — bambuwater.web.id (PHP 8.4 + SQLite, v3.0.0.0)

> Panduan pemulihan untuk operator/AI sesi berikutnya. Prinsip: situs harus bisa
> dihidupkan kembali oleh siapa pun yang membaca dokumen ini.

## 0. Peta Operasional
| Komponen | Lokasi | Catatan |
|---|---|---|
| App | PHP 8.4 murni, root repo (= `public_html`) | Front controller `index.php` |
| DB | SQLite `data/cms.sqlite` (PDO, WAL) | Dibuat otomatis saat request pertama |
| Konten | `data/*.json` | Sumber konten abadi (Z2) — jangan edit asal saat CMS hidup |
| Cache | `data/cache/` | File cache sitemap/search-index — aman dihapus kapan pun |
| Rahasia | `config.php` (default) atau env `BWN_*` | TIDAK pernah di-commit |
| Versi | `config.php` APP_VERSION → `/api/version` | Sumber kebenaran |
| Kesehatan | `/api/health` | probe DB |
| Uji | `bash tests/e2e.sh` | 83 assertion, instance terisolasi |

## 1. Jalankan / Restart (lokal & sandbox)
```bash
php -S 0.0.0.0:8080 index.php          # dari root repo
curl -s http://localhost:8080/api/health
```
Di cPanel tidak ada "restart" — PHP-FPM per akun; bila situs 500, cek §3.

## 2. Situs mati / halaman putih — urutan diagnosa
1. `curl -s https://bambuwater.web.id/api/health` → `{"status":"healthy"}`? DB hidup.
2. Cek error log cPanel → Errors (cari `PHP Fatal error`, `Permission denied`).
3. `data/` writable? chmod 755 (folder) / 644 (file) via File Manager.
4. PHP version = 8.4? (Select PHP Version). PHP < 8.1 → fatal (match, readonly, dll).
5. `.htaccess` masih ada? Hapus/rename → coba lagi (kalau pulih: mod_rewrite/allowoverride bermasalah → hubungi hosting).
6. Korup DB? `mv data/cms.sqlite data/cms.sqlite.bak` → request baru = DB kosong sehat (pesan lama hilang — restore dari backup, lihat §5).

## 3. Error umum & solusi
| Gejala | Penyebab | Solusi |
|---|---|---|
| 500 setelah upload | PHP < 8.1 / file `.htaccess` tidak diizinkan | Pilih PHP 8.4; hubungi hosting soal AllowOverride |
| Halaman kota 404 semua | mod_rewrite off | Minta hosting aktifkan mod_rewrite + AllowOverride All |
| `/admin` login tidak bisa | password lupa | Hapus `data/cms.sqlite` → DB baru ter-seed `admin/bambuwater2025` → login → ganti password (data pesan lama hilang — backup dulu!) |
| Email notifikasi tidak datang | mail() diblokir/SPF | Set SPF/DKIM domain; atau kosongkan kolom Email Notifikasi; upgrading ke SMTP lihat docs/SECURITY.md |
| Sitemap XML error | cache korup | Hapus isi `data/cache/` (selalu aman) |

## 4. Backup (rutin)
- cPanel → **Backup → Download a Full Account Backup** (mingguan), atau minimal:
- File Manager: unduh `data/` (JSON + cms.sqlite) — itu seluruh state dinamis.
- Kode: repo Git adalah backup; `git pull` = restore.

## 5. Rollback deploy
1. Sebelum deploy baru: File Manager → kompres/unduh `public_html` lama sebagai ZIP (arsip tanggal).
2. Bila deploy baru bermasalah: hapus file baru, ekstrak arsip lama kembali, selesai (< 5 menit).
3. Database: arsip `data/cms.sqlite` + WAL/SHM dijamin konsisten bila diunduh bersama (WAL aktif — unduh ketiganya).

## 6. Keamanan insiden
1. Dugaan login liar: cek pesan/log; ganti password admin (Akun).
2. Rate limit otomatis 5 percobaan/5 menit/IP pada login; honeypot + CSRF aktif di form.
3. Bila DB bocor: rotasi password admin + hubungi hosting (database berisi pesan pelanggan — UU PDP).

## 7. Verifikasi pasca-perubahan (wajib)
```bash
bash tests/e2e.sh          # 83 assertion harus 83/83
php -l config.php          # dsb. setiap file yang diedit
```
Lalu di produksi: buka `/`, `/kota/bandung`, `/admin`, `/sitemap.xml`, `/api/health`.
