# 🔐 Model Keamanan — Bambu Water Nusantara v3.0.0.0

Inventaris kontrol keamanan aplikasi, ancaman yang termitigasi, dan risiko residual
yang membutuhkan tindakan level hosting. Terakhir diperbarui: 2026-11-01.

## 1. Kontrol yang aktif

### Autentikasi & sesi
| Kontrol | Implementasi |
|---|---|
| Penyimpanan password | `password_hash()` (bcrypt, cost default PHP 8.4) — plaintext tak pernah disimpan |
| Verifikasi | `password_verify()` + rehash otomatis saat algoritma default PHP berubah |
| Regenerasi sesi | `session_regenerate_id(true)` saat login sukses |
| Cookie sesi | `BWNSESS`, `HttpOnly`, `SameSite=Lax`, `Secure` otomatis saat HTTPS, `use_strict_mode=1`, `use_only_cookies=1` |
| Kedaluwarsa | Idle admin 2 jam (`bwn_last_activity`); lifetime cookie = sesi browser |
| Logout | Hapus session + cookie + data server-side |
| **Wajib ganti password default** | Selama `bambuwater2025` masih valid, seluruh menu admin diarahkan paksa ke Akun |
| Petunjuk password default | Hanya tampil selama default masih aktif; setelah diganti hilang |
| Rate limit login | 5 percobaan / 5 menit / IP (`REMOTE_ADDR`; XFF tidak dipercaya) + brake 1 detik antar percobaan |

### Aplikasi
| Kontrol | Implementasi |
|---|---|
| SQL injection | 100% PDO prepared statement (semua query admin/kontak/override) |
| XSS | Output lewat `e()` = `htmlspecialchars(ENT_QUOTES|ENT_SUBSTITUTE)`; tanpa kolom HTML mentah |
| CSRF | Token per-sesi (`random_bytes(32)`, `hash_equals`) di **semua** form admin + kontak; POST admin tanpa token = HTTP 419 |
| Brute force konten | Rate limit form kontak 5/menit/IP |
| Spam form | Honeypot `website` — bot dijawab sukses tanpa disimpan |
| Validasi input | Panjang + format (telepon regex, email FILTER_VALIDATE, whitelist order_type) |
| Upload file | Tidak ada fitur upload — permukaan serangan minimal |
| Path traversal | Tidak ada parameter file dari user; slug difilter regex `[a-z0-9-]` |
| Error handling | `APP_DEBUG=false` di produksi: display_errors off, error tak bocor |
| CSP | `default-src 'self'; script-src 'self' 'nonce-…' …; frame-ancestors 'self'; object-src 'none'; base-uri 'self'; form-action 'self'` — inline script hanya dengan nonce per-request; JSON-LD data block tak terdampak |
| Header | `X-Frame-Options: SAMEORIGIN`, `X-Content-Type-Options: nosniff`, `Referrer-Policy: strict-origin-when-cross-origin`, `Permissions-Policy` (kamera/mic/geo off), HSTS (opsional, lihat §3) |
| Admin noindex | `X-Robots-Tag: noindex, nofollow, noarchive` dari PHP + `.htaccess` + meta robots |

### Infrastruktur (Apache/.htaccess — 2 lapis + router)
| Kontrol | Implementasi |
|---|---|
| Database & data privat | `data/` dilindungi `RewriteRule` **dan** `<FilesMatch>` (sqlite/json/log/bak/env/… → 403); bekerja juga bila mod_rewrite mati |
| Kode internal | `config.php`, `admin-cms.php`, `lib/`, `templates/`, `tools/`, `data/cache/` tak dilayani langsung |
| Eksekusi tools web | Guard `PHP_SAPI !== 'cli'` → 403 (ping-indexnow, build-keywords) |
| Directory listing | `Options -Indexes` |
| Server signature | `ServerSignature Off` |
| HTTPS | Blok 301 siap-pakai (diaktifkan setelah AutoSSL, komentar di `.htaccess`) + `upgrade-insecure-requests` saat HTTPS |
| Kredensial | Tidak ada secret di repo; override via env (`BWN_*`) atau `config.local.php` (di .gitignore) |
| Token/API | IndexNow key = publik by design (file verifikasi publik) |

## 2. Ancaman yang termitigasi (ringkas)
SQLi, XSS tersimpan/reflected, CSRF, session fixation/hijack (nonce+regeneration+HttpOnly+Secure),
brute force login, spam form, enumeration file sensitif, clickjacking, MIME sniffing,
directory listing, eksekusi tools via web, caching halaman admin (admin `no-store` tidak pernah
di-cache publik — hanya aset statis yang diberi TTL panjang).

## 3. Risiko residual (jujur) & tindakan level hosting
| Risiko | Dampak | Mitigasi yang disarankan |
|---|---|---|
| SQLite di dalam `public_html` | Bila **kedua** lapis proteksi Apache dimatikan pihak hosting, file bisa diunduh | Tingkat lanjut: pindah `data/` ke luar webroot (env `BWN_DATA_DIR`, mis. `/home/USER/bwn-data`) — didukung penuh oleh konfigurasi |
| Deliverabilitas `mail()` | Notifikasi bisa masuk spam / ditolak tanpa SPF/DKIM | Set SPF+DKIM domain; bila perlu, upgrade ke SMTP autentik (PHPMailer) — antrian P1 |
| HSTS menunggu SSL stabil | Header HSTS dikirim sejak awal (15552000 dtk); bila SSL domain gagal, pengunjung tak bisa kembali via http | Pastikan AutoSSL aktif SEBELUM mengaktifkan blok 301 https; bila insiden SSL: hapus baris HSTS di .htaccess |
| CSP `style-src 'unsafe-inline'` | Eksploitasi style-inline tetap mungkin (desain memakai style inline ekstensif) | Jangka panjang: migrasikan style inline ke class; tak mengurangi proteksi script |
| Berbagi hosting (noise tetangga) | Kontensi CPU/IO | File-cache sitemap/index mengurangi beban; SQLite WAL untuk konkurensi baca |
| `tests/` & `docs/` di webroot | Tidak dieksekusi (404/deny), tapi menambah permukaan info | Boleh dihapus dari `public_html` saat deploy produksi (lihat panduan deploy §11) |

## 4. Ritel keamanan berkala
1. **Mingguan**: cek Admin → Pesan (spam?), ganti password bila dicurigai.
2. **Bulanan**: unduh backup `data/`; cek versi PHP hosting masih 8.4.
3. **Setelah insiden**: rotasi password admin; periksa `data/cms.sqlite` (tabel `contact_messages` untuk injeksi aneh; `admin_users` untuk user tak dikenal).
4. **Update PHP minor** (8.4.x): aman, tanpa perubahan kode.

## 5. Pelaporan
Temuan keamanan → hubungi pengelola. Harap **jangan** uji penetrasi tanpa izin pemilik domain.
