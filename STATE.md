# 🧭 STATE.md — Bambu Water Nusantara (bambuwater.web.id)

> SATU-SATUNYA bacaan wajib di awal sesi. Diperbarui di AKHIR tiap sesi.
> Riwayat lengkap: `worklog.md` (append-only) + `repo-legacy-worklog.md` (arsip).

## a. Identitas
| | |
|---|---|
| **Situs** | PT Bambu Water Nusantara — bambuwater.web.id |
| **Repo sumber** | github.com/gunaracorp/bambu-water (branch `main`) |
| **Arah arsitektur (BARU — keputusan pemilik)** | **PHP 8.4 Shared Hosting Edition menuju 3.0.0.0**: PHP CMS (`php-cms/`) menjadi aplikasi utama; Next.js/Node.js akan DIHAPUS bertahap setelah verifikasi; clean URL tanpa hash; target shared hosting cPanel |
| **Stack PHP CMS** | PHP 8.4 murni · PDO SQLite (WAL) · front controller + .htaccess clean URL · admin CMS · SEO Universalis · tanpa Node.js, tanpa build step |
| **Stack lama (menunggu penghapusan)** | Next.js 16 (App Router) · React 19 · TS · Tailwind 4 · shadcn/ui · Framer Motion · Prisma/SQLite · Bun — hash-routing `#/{locale}/{slug}` (melanggar standar clean URL 3.0.0.0) |
| **Versi aktif** | **v0.5.2 "Dewan Audit"** (2026-10-31) — audit menyeluruh + 4 perbaikan keamanan/fungsional + uji E2E 34/34 di PHP 8.4.13 sungguhan |
| **Sesi terakhir** | (2026-10-31) Audit P0 lengkap seluruh repo (289 file): peta Next.js untuk penghapusan aman, audit keamanan PHP CMS, uji runtime PHP 8.4.13 (E2E 34/34), perbaikan bug override artikel (admin→publik kini bekerja), rate limit login admin per IP, hardening XFF→REMOTE_ADDR, petunjuk password default kondisional. Laporan lengkap: **`AUDIT-2026-10-31.md`** |

## b. Skor 12 Area (akhir sesi ini)
| # | Area | Sebelum → Sesudah | PJ |
|---|---|---|---|
| 1 | Arsitektur & Kode | 92 → 94 (#audit penuh, 4 fix terverifikasi) | jalur 3.0.0.0 jelas |
| 2 | Performa | 89 → 89 | — (file-cache P1) |
| 3 | SEO | 97 → 97 | — (stabil) |
| 4 | UX/UI & Branding | 90 → 90 | — |
| 5 | Aksesibilitas | 83 → 83 | — |
| 6 | Keamanan | 84 → 88 (#3 fix keamanan + uji) | sisa: FilesMatch DB, header frame, SMTP |
| 7 | Monetisasi | 80 → 80 | — |
| 8 | Analitik & Data | 70 → 70 | ⏳ ID GA4/Clarity pemilik |
| 9 | Konten | 95 → 95 | 2.649 hal PHP hidup |
| 10 | Dokumentasi | 90 → 94 (AUDIT-2026-10-31.md) | rencana P0/P1/P2 tertulis |
| 11 | Legal & Kepatuhan | 85 → 85 | cookie notice P1 |
| 12 | Operasi & Resiliensi | 78 → 80 | skrip E2E otomatis tersimpan |

## c. Kapabilitas TEGAK (G3 — DILARANG DIRUSAK)
- **PHP CMS mandiri** (`php-cms/`) — terbukti NOL dependensi ke folder lain; jalankan `php -S 0.0.0.0:8080 index.php`.
- **Auto-Pages Engine PHP**: `data/{cities,conditions,services,bamboo,articles}.json` (2.524/60/25/8/7) + router regex + sitemap 2.649+1.386 URL.
- **Admin CMS** `/admin`: login (CSRF + bcrypt + regenerate id + rate limit IP 5/5menit), pesan, artikel override (KINI benar-benar tampil di publik), pengaturan, akun.
- **SEO Universalis**: meta head penuh, JSON-LD (Organization/Breadcrumb/FAQ/Product/BlogPosting), llms.txt, IndexNow, RSS, robots 26 UA.
- **Proteksi file sensitif**: router php -S (403) + `.htaccess` rewrite block.
- **WhatsApp float** 0813-1651-6524 — kanal konversi utama.
- *(Arsip Next.js)* Hash-routing hydration-proof + i18n 30 bahasa + Prisma contact — tetap utuh di `src/` sampai penghapusan terjadwal; DILARANG dikembangkan lebih lanjut (arah 3.0.0.0).

## d. Utang Teknis & Setengah Jalan
- **Penghapusan Next.js belum dilakukan** (P0 #7) — `src/`, manifes, prisma, scripts/i18n, Caddyfile, examples, mini-services, tests scaffold, branch vercel masih ada.
- SQLite di dalam webroot — perlu `<FilesMatch>` lapis kedua (P0 #2).
- Tidak ada notifikasi email pesanan (SMTP) — P0 #3.
- Akses langsung `/admin-cms.php` masih dieksekusi di Apache — P0 #4.
- Header frame/HSTS/CSP belum ada — P0 #5.
- Redirect hash→clean belum dipetakan — P0 #6.
- Cookie consent belum ada (P1); GA4/Clarity menunggu ID pemilik (P2).
- `search-index.json` & sitemap tanpa file-cache (P1, hemat CPU shared hosting).

## e. PIAGAM 2125 (final)
> Dilihat dari tahun 2125: Bambu Water Nusantara adalah penjaga warisan — bahwa air sederhana dari batang bambu tanah Jawa pernah menyembuhkan, dan tetap disajikan dengan jujur, ilmiah, dan berwibawa. Kami membangun rumah digital yang tak lekang: konten lebih tahan lama dari kode, URL yang tak pernah patah, dan kejujuran yang tak musim. Setiap halaman kota, tiap istilah glossarium, tiap data sains — adalah sumbang mahar bagi generasi yang tak akan pernah kami temui. Kami menolak tren sesaat, memeluk standar abadi: aksesibel bagi semua, cepat bagi semua, jujur kepada semua. Teknologi akan ganti wajah seratus kali; janji kami tidak. *Air yang jujur, warisan yang abadi.*

## PETA MODUL ABADI (ringkas)
| Modul | Fungsi | Status |
|---|---|---|
| PHP CMS + Admin | Situs utama + CMS (arah 3.0.0.0) | ✅ Tegak (v0.5.2, E2E 34/34) |
| Auto-Pages Engine PHP | 2.649 hal programatik + 1.386 tag | ✅ Tegak |
| SEO Universalis | Google/Bing/Yandex/AI + IndexNow | ✅ Tegak |
| Kontak/WA | Lead capture SQLite (WA float) | ✅ Tegak (email P0) |
| Sistem Versi S1 | SemVer + badge + /api/version | ✅ Tegak (v0.5.2, 4 titik sinkron) |
| Runbook S12 | Pemulihan operasi | ✅ Tegak (pembaruan PHP-first menyusul) |
| **Mirror Next.js** | `src/` 213 file hash-routing | 🟠 BEKU — menunggu penghapusan aman (P0 #7) |

## f. NEXT-QUEUE (prioritas sesi berikutnya)
| Pri | Tugas | Effort |
|---|---|---|
| P0 | `.htaccess` `<FilesMatch>` deny sqlite/json + blokir `admin-cms.php` langsung + header frame/HSTS | 30m |
| P0 | PHPMailer + SMTP cPanel: notifikasi pesanan baru (fallback log) | 2j |
| P0 | Peta redirect hash→clean + tabel verifikasi | 1j |
| P0 | **Penghapusan Next.js bertahap** + restuktur repo PHP-first + rewrite README/STATE/RUNBOOK | 4j |
| P0 | Rilis 3.0.0.0: tag + CHANGELOG + ZIP deploy + panduan cPanel | 1j |
| P1 | Cookie consent + ganti-password wajib login pertama | 1j |
| P1 | File-cache sitemap & search-index | 1j |
| P1 | Self-host font + audit WCAG kontras | 2j |
| P2 | Roadmap 99 tahap (11×9) — setelah validasi fakta bisnis pemilik | 3j |
