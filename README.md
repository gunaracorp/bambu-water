# 🌿 Bambu Water Nusantara — PHP 8.4 + SQLite (v3.0.0.0 "Nusantara PHP Murni")

Situs resmi **bambuwater.web.id** — aplikasi **PHP murni + SQLite** untuk shared hosting cPanel.
Tanpa Node.js, tanpa npm/bun, tanpa build step, tanpa framework. Upload ke `public_html` dan situs hidup.

> **Era Next.js telah berakhir.** Sejak v3.0.0.0 repo ini 100% PHP (lihat `docs/MODERNIZATION-REPORT-2026-11-01.md`).

## ✨ Isi Paket
| Komponen | Detail |
|---|---|
| **Halaman publik** | Beranda 14 section + 24 halaman inti + **2.524 kota** + 60 kondisi kesehatan + 25 layanan + 8 jenis bambu + 7 artikel = **2.649 URL** (server-side render murni) |
| **Admin CMS** | `/admin` — dashboard, pesan masuk (buka/tandai/hapus), pengaturan situs (nama, WA, email, URL, **email notifikasi**), editor artikel (override aman), ganti password (**wajib** saat pertama login) |
| **SEO** | Meta unik per URL, canonical, hreflang id/en, OG + Twitter Card, JSON-LD (Organization/Breadcrumb/FAQ/Product/BlogPosting), `sitemap.xml` dinamis 4.035 URL, robots.txt 26 UA, `llms.txt`, RSS, IndexNow |
| **Keamanan** | CSP + nonce, `.htaccess` berlapis (FilesMatch deny), PDO prepared statements, `htmlspecialchars` penuh, CSRF token, rate limit, password_hash, sesi hardening (HttpOnly/SameSite/Secure/strict), honeypot form |
| **Performa** | Font self-hosted WOFF2, WebP otomatis + fallback JPEG, hero LCP `fetchpriority="high"`, dimensi gambar anti-CLS, file-cache sitemap & search-index, kompresi gzip |
| **Interaktif** | Command palette Ctrl+K (2.649 halaman), kalkulator kesehatan, lacak pesanan, filter live, reveal-on-scroll |
| **i18n** | ID penuh + EN parsial (`?lang=en`, cookie persisten), fallback ID |

## 🚀 Instalasi Shared Hosting (cPanel) — tanpa SSH
Panduan lengkap bergambar: **[docs/DEPLOYMENT-CPANEL.md](docs/DEPLOYMENT-CPANEL.md)**.

1. Upload ZIP rilis ke `public_html/` via **File Manager** → Extract.
2. cPanel → **Select PHP Version / MultiPHP Manager** → pilih **PHP 8.4**.
3. Pastikan folder `data/` writable (755/775) — SQLite `data/cms.sqlite` dibuat otomatis saat request pertama.
4. Buka domain → **login pertama:** `admin` / `bambuwater2025` → Anda **dipaksa** ke menu Akun → ganti password.
5. Menu **Pengaturan** → cek URL situs (canonical & sitemap) + opsional isi **Email Notifikasi**.

## 🖥️ Jalankan Lokal (tanpa Apache)
```bash
php -S 0.0.0.0:8080 index.php
# buka http://localhost:8080
```

## 🧪 Uji E2E (produksi-aman)
```bash
bash tests/e2e.sh        # instance terisolasi — tidak menyentuh DB lokal/produksi
# PHP_BIN=/path/php8.4 bash tests/e2e.sh   # bila php default bukan 8.4
```
83 assertion: rute publik, SEO endpoints, kanonikalisasi 301, proteksi file, header keamanan,
cache, form kontak (CSRF/honeypot), siklus admin lengkap.

## 🧩 Struktur
```
├── index.php          # Front controller + router clean URL
├── config.php         # ⭐ Satu file konfigurasi (versi, admin, kontak, env override)
├── admin-cms.php      # Panel CMS tunggal (login, pesan, artikel, pengaturan, akun)
├── lib/               # helpers (escape/CSRF/cache/CSP/i18n) + db (PDO SQLite WAL)
├── templates/         # layout, pages (inti), programmatic, tags, sitemap, feed, llms
├── data/              # Konten JSON (2.524 kota dll) + cms.sqlite (dibuat otomatis) + cache/
├── assets/            # style.css (design system), app.js, images (JPG+WebP), fonts (WOFF2)
├── tools/             # CLI: ping-indexnow, build-keywords (diblokir via web)
├── tests/             # e2e.sh — 83 assertion, instance terisolasi
└── docs/              # Panduan deploy cPanel, keamanan, laporan modernisasi, arsip
```

## 🔐 Catatan Keamanan
- `data/` (database + cache) dilindungi **dua lapis** di Apache: `RewriteRule` + `<FilesMatch>` deny.
- Tingkat lanjut: pindahkan `data/` ke luar `public_html` dengan env `BWN_DATA_DIR` (lihat docs/SECURITY.md).
- Password default `bambuwater2025` **wajib diganti** — aplikasi mengunci dashboard sampai diganti.

## 📌 Kejujuran Teknis (Dewan-46)
- i18n EN parsial (nav/footer/label) — konten programatik fallback ID (pola sama dengan era sebelumnya).
- Lacak pesanan = simulasi deterministik — bukan integrasi kurir.
- Notifikasi email memakai `mail()` hosting — deliverabilitas bergantung SPF/DKIM domain (docs/SECURITY.md).
- E2E dijalankan di PHP 8.4.13 + server bawaan; Apache `.htaccess` teruji struktural — verifikasi penuh
  dilakukan sekali di cPanel produksi (checklist tersedia di docs/DEPLOYMENT-CPANEL.md).
