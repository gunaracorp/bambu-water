# 🧭 STATE.md — Bambu Water Nusantara (bambuwater.web.id)

> SATU-SATUNYA bacaan wajib di awal sesi. Diperbarui di AKHIR tiap sesi.
> Riwayat lengkap: `worklog.md` (append-only) + `docs/archive/` (arsip era ganda).

## a. Identitas
| | |
|---|---|
| **Situs** | PT Bambu Water Nusantara — bambuwater.web.id |
| **Repo** | github.com/gunaracorp/bambu-water (branch kerja: `modernization/php-only`; target merge: `main`) |
| **Arsitektur (FINAL)** | **PHP 8.4 murni + SQLite (PDO WAL)** — aplikasi di root repo, siap `public_html` cPanel. Next.js/Node.js/npm/bun **dihapus total** sejak v3.0.0.0 |
| **Versi aktif** | **v3.0.0.0 "Nusantara PHP Murni"** (2026-11-01) |
| **Sesi terakhir** | (2026-11-01) Modernisasi penuh: eliminasi Next.js terverifikasi, restrukturisasi root = aplikasi, keamanan berlapis (CSP+nonce, FilesMatch, sesi hardening, wajib ganti password, honeypot), performa (font self-hosted, WebP, LCP hero, file-cache), E2E otomatis 83/83 PASS, dokumentasi & panduan cPanel |

## b. Skor 12 Area (akhir sesi ini)
| # | Area | Sebelum → Sesudah | Catatan |
|---|---|---|---|
| 1 | Arsitektur & Kode | 94 → 96 | root = aplikasi; nol dependensi runtime |
| 2 | Performa | 89 → 93 | font lokal, WebP, LCP priority, file-cache (ukuran sesungguhnya menunggu produksi) |
| 3 | SEO | 97 → 97 | dipertahankan penuh + 301 kanonik + og-image benar |
| 4 | UX/UI & Branding | 90 → 90 | identitas visual tak diubah |
| 5 | Aksesibilitas | 83 → 86 | aria-expanded dinamis, aria-controls, label menu, dimensi gambar |
| 6 | Keamanan | 88 → 94 | CSP+nonce, FilesMatch, sesi hardening, wajib ganti pass, honeypot |
| 7 | Monetisasi | 80 → 80 | — |
| 8 | Analitik & Data | 70 → 74 | consent gate siap; ID GA4/Clarity menunggu pemilik |
| 9 | Konten | 95 → 95 | 2.649 halaman hidup |
| 10 | Dokumentasi | 94 → 96 | laporan modernisasi + panduan cPanel + SECURITY.md |
| 11 | Legal & Kepatuhan | 85 → 88 | consent analitik + honeypot + privasi utama |
| 12 | Operasi & Resiliensi | 80 → 90 | tests/e2e.sh permanen (83 asersi) + RUNBOOK PHP |

## c. Kapabilitas TEGAK (G3 — DILARANG DIRUSAK)
- **PHP CMS mandiri** di root repo — nol dependensi folder luar; `php -S 0.0.0.0:8080 index.php`.
- **Auto-Pages Engine**: `data/{cities,conditions,services,bamboo,articles}.json` (2.524/60/25/8/7) + router regex + sitemap 2.649+1.386 URL.
- **Admin CMS** `/admin`: login (CSRF + bcrypt + regenerate id + rate limit IP + wajib ganti password), pesan, artikel override (tampil di publik), pengaturan (+email notifikasi), akun.
- **SEO Universalis**: meta head penuh, JSON-LD, llms.txt, IndexNow, RSS, robots 26 UA — UTUH.
- **Proteksi file sensitif**: `.htaccess` 2 lapis + router 403 + guard CLI tools.
- **WhatsApp float** 0813-1651-6524 — kanal konversi utama.
- **tests/e2e.sh** — 83 assertion, instance terisolasi (produksi-aman).

## d. Utang Teknis & Setengah Jalan
- Verifikasi penuh di cPanel produksi sekali jalan (checklist DEPLOYMENT-CPANEL.md §8): mod_rewrite aktif, PHP 8.4 terpilih, HTTPS/AutoSSL, blok HTTPS-301 di .htaccess diaktifkan.
- SMTP autentik (PHPMailer) bila deliverabilitas `mail()` kurang — fallback log tersedia.
- Roadmap konten 99 tahap (P2) — menunggu validasi fakta bisnis pemilik.
- GA4/Clarity menunggu ID pemilik (consent gate sudah siap).

## e. PIAGAM 2125 (final)
> Dilihat dari tahun 2125: Bambu Water Nusantara adalah penjaga warisan — bahwa air sederhana dari batang bambu tanah Jawa pernah menyembuhkan, dan tetap disajikan dengan jujur, ilmiah, dan berwibawa. Kami membangun rumah digital yang tak lekang: konten lebih tahan lama dari kode, URL yang tak pernah patah, dan kejujuran yang tak musim. Setiap halaman kota, tiap istilah glossarium, tiap data sains — adalah sumbang mahar bagi generasi yang tak akan pernah kami temui. Kami menolak tren sesaat, memeluk standar abadi: aksesibel bagi semua, cepat bagi semua, jujur kepada semua. Teknologi akan ganti wajah seratus kali; janji kami tidak. *Air yang jujur, warisan yang abadi.*

## PETA MODUL ABADI (ringkas)
| Modul | Fungsi | Status |
|---|---|---|
| PHP CMS + Admin (root) | Situs utama + CMS | ✅ Tegak (v3.0.0.0, E2E 83/83) |
| Auto-Pages Engine | 2.649 hal programatik + 1.386 tag | ✅ Tegak |
| SEO Universalis | Google/Bing/Yandex/AI + IndexNow | ✅ Tegak |
| Kontak/WA | Lead capture SQLite (+email opsional) | ✅ Tegak |
| Sistem Versi S1 | SemVer + /api/version | ✅ Tegak (v3.0.0.0) |
| Runbook S12 | Pemulihan operasi PHP | ✅ Tegak (ditulis ulang) |
| E2E Suite | tests/e2e.sh terisolasi | ✅ Tegak (83 asersi) |
| ~~Mirror Next.js~~ | dihapus seluruhnya | 🗑️ SELESAI DIHAPUS v3.0.0.0 |

## f. NEXT-QUEUE (prioritas sesi berikutnya)
| Pri | Tugas | Effort |
|---|---|---|
| P0 | **Merge `modernization/php-only` → `main` + deploy cPanel** (panduan tersedia) + verifikasi checklist produksi | 1j |
| P0 | Aktifkan blok HTTPS-301 di `.htaccess` + submit sitemap ke GSC/Bing/Yandex (file verifikasi siap) | 30m |
| P1 | Ukur CrUX/PageSpeed pasca-deploy (butuh trafik/PSI API key pemilik) | 30m |
| P1 | SMTP autentik (PHPMailer atau SMTP native) bila `mail()` tidak sampai | 2j |
| P2 | Roadmap konten 99 tahap — setelah validasi fakta bisnis pemilik | 3j |
