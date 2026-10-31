# Changelog — bambuwater.web.id

Format: [Keep a Changelog](https://keepachangelog.com) · SemVer · **1 tugas = 1 versi** (S1).

## [3.1.0.0] — 2026-11-01 — "Spektrum v2 + 30 Bahasa" (Desain Profesional & i18n Penuh)

Respons umpan balik pemilik: tampilan v3.0.0.0 dinilai kurang profesional dan kamus 30 bahasa era
Next.js ikut terhapus. Rilis ini mengembalikan keduanya di atas fondasi PHP murni — tanpa framework,
tanpa build tool, tetap 100% kompatibel cPanel.

### Added — Desain (Opsi A)
- **Design system "Spektrum v2"** (`assets/style.css` 239 → ±470 baris): design tokens berlapis
  (layered shadow, ring fokus, gradien brand/emas), hero gradient-mesh + blob mengambang + badge
  berdenyut + teks gradien, statbar kaca menimpa hero, kartu dengan aksen gradien saat hover,
  ikon gradien, testimoni bergaya kutipan, timeline bertitik, tab pill gradien, FAQ dengan status
  terbuka beraksen, tombol dengan efek kilau, footer dengan garis gradasi & hover geser.
- **Animasi bermakna**: reveal-on-scroll dengan stagger otomatis untuk grid (IntersectionObserver),
  hero zoom lambat, bayangan navbar saat scroll, tombol kembali-ke-atas.
- **Aksesibilitas**: `.reveal` hanya menyembunyikan konten saat JS aktif (tanpa JS = semua terlihat),
  `prefers-reduced-motion` mematikan seluruh gerak, fokus tetap kontras.

### Added — i18n 30 Bahasa (Opsi B)
- **Kamus dipulihkan dari git** (0e042bc era Next.js) → `lib/i18n/{kode}.json` (aset aplikasi,
  terproteksi RewriteRule `lib/` + FilesMatch `*.json`, kebal `BWN_DATA_DIR`).
- **Fakta jujur yang ditemukan**: kamus lama hanya benar-benar menerjemahkan ~8 bahasa
  (en 90%, zh 79%, es/pt 57%, ar 52%, hi 46%, ru 8%, bn 3%); 21 bahasa lain ternyata masih
  teks Indonesia berlabel ganda. Rilis ini menambah **terjemahan inti UI (32 string) untuk
  19 bahasa tambahan** (de, fr, it, ja, ko, nl, pl, tr, vi, ms, th, fil, sw, fa, he, ur, uk, bn, ru).
- `t()` kini lookup kamus per bahasa dengan fallback berlapis (map → EN template → ID);
  `lang()` memvalidasi 30 kode; **switcher bahasa** `<details>` di header (berfungsi tanpa JS),
  **hreflang 30 bahasa + x-default**, `og:locale` per bahasa, **dir="rtl"** untuk ar/fa/he/ur
  beserta penyesuaian CSS RTL.
- Konten hasil-generate (2.524 kota dll.) tetap Indonesia — fallback transparan, sama seperti
  perilaku situs lama, kini terdokumentasi.

### Changed
- `APP_VERSION` 3.1.0.0 (cache-busting `?v=` otomatis di CSS/JS).

### Security
- Router `php -S`: blokir `/data|/lib|/templates` **tidak lagi bergantung keberadaan file**
  (defense in depth, paritah `.htaccess`; sebelumnya 404 saat file absen, kini konsisten 403).

### Tests
- Suite E2E: **83 → 93 asersi** — tambah: hreflang ≥30, terjemahan DE (nav+CTA), RTL AR,
  lang=ja, kode bahasa asing aman, `?lang=XSS` tidak bocor, switcher & tombol-to-top ada,
  kamus `lib/i18n/*.json` diblokir 403, pra-buat `cms.sqlite` + bersih-bersih rate-limit antar-run
  (deterministik), asersi versi kini agnostik (3.x).
- Hasil: **93/93 PASS** pada PHP 8.4.13 (binary produksi target).

## [3.0.0.0] — 2026-11-01 — "Nusantara PHP Murni" (Next.js Eliminated)

Teknis dari spesifikasi modernisasi penuh: repo kini **aplikasi PHP murni** — Next.js/Vercel/Node.js
dihapus seluruhnya, arsitektur direstrukturisasi PHP-first, keamanan berlapis, performa & SEO ditingkatkan,
lengkap dengan suite E2E otomatis dan panduan deploy cPanel tanpa SSH.

### Removed (Phase 2 — Next.js Elimination)
- `src/` (213 file: App Router, komponen, i18n 30 bahasa, UI kit), `public/` (gambar duplikat),
  `prisma/`, `examples/websocket`, `mini-services`, `scripts/i18n-*.ts`, `tests/*.sh` scaffold lama.
- Konfigurasi Next/Vercel: `next.config.ts`, `package.json`, `bun.lock`, `tsconfig.json`,
  `tailwind.config.ts`, `postcss.config.mjs`, `eslint.config.mjs`, `components.json`, `Caddyfile`, `i18n-PATTERN.md`.
- **Verifikasi**: `php-cms/` terbukti nol referensi ke semua folder tersebut (scan lintas-repo sebelum hapus).
  Nol dependensi Node.js/npm/bun tersisa. Arsip dokumen era lama dipindah ke `docs/archive/`.

### Changed (Phase 3 — Restrukturisasi PHP-First)
- **Isi `php-cms/` dipindah ke root repo** — root repo = aplikasi yang bisa di-upload langsung ke `public_html`.
- Path fix `admin-cms.php` (mode standalone) pasca relokasi.
- Versi 3.0.0.0, codename "Nusantara PHP Murni"; `.gitignore` ditulis ulang untuk stack PHP.

### Security (Phase 5)
- **`.htaccess` berlapis**: `<FilesMatch>` deny (sqlite/json/log/bak/env + config.php/admin-cms.php),
  blokir `lib/ templates/ tools/ data/cache/`, lapis kedua selain rewrite.
- **CSP + nonce per-request** via PHP (`send_security_headers()`): `script-src 'self' 'nonce-…'`,
  `frame-ancestors 'self'`, `object-src 'none'`, `upgrade-insecure-requests` (HTTPS). Handler `onclick`
  inline dipindah ke `app.js` — tak ada lagi script inline tanpa nonce (JSON-LD data block tak terdampak).
- **Header keamanan lengkap**: X-Frame-Options, X-Content-Type-Options, Referrer-Policy, Permissions-Policy,
  HSTS (opsional di .htaccess), `X-Robots-Tag: noindex` untuk /admin (kini dari PHP + .htaccess).
- **Hardening sesi**: `session.use_strict_mode`, cookie `HttpOnly`+`SameSite=Lax`+`Secure` otomatis saat HTTPS,
  nama sesi kustom `BWNSESS`, kedaluwarsa idle admin 2 jam, logout menghapus session + cookie.
- **Wajib ganti password default** — admin diarahkan paksa ke menu Akun selama password bawaan aktif.
- **Kanonikalisasi URL 301**: trailing slash & huruf besar → URL bersih (query dipertahankan) — mencegah duplikat.
- **Honeypot anti-spam** pada form kontak (bot dijawab "sukses" tanpa disimpan).

### Performance (Phase 6)
- **Font self-hosted WOFF2** (Playfair Display 700 + Plus Jakarta Sans variable 400–800, latin, `font-display: swap`,
  preload 2 file kritis) — Google Fonts CDN dihapus (2 request render-blocking hilang).
- **WebP transparan**: 8 gambar dikonversi (hemat 11–43%) + negosiasi otomatis via .htaccess, fallback JPEG.
- **LCP hero** kini `<img fetchpriority="high">` + `preload` (sebelumnya CSS background — tidak diprioritaskan browser).
- **Dimensi gambar** (`width`/`height`) di semua `<img>` via `img_dim()` — anti layout shift (CLS).
- **File-cache** sitemap (2.649+1.386 URL) & search-index (TTL 1 jam/10 menit) — dibangun ulang otomatis saat admin
  menyimpan artikel/pengaturan; header `X-Cache: HIT/MISS` untuk verifikasi.
- `og-image.jpg` 1200×630 sungguhan (sebelumnya klaim dimensi og tidak sesuai file).

### Added
- **`tests/e2e.sh`** — suite E2E otomatis **83 assertion** (instance terisolasi via `BWN_DATA_DIR`,
  produksi-aman): rute publik, SEO endpoints, kanonikalisasi, 403 file sensitif, header keamanan,
  kualitas HTML, cache, form kontak (CSRF/honeypot/validasi), siklus admin lengkap. **83/83 PASS di PHP 8.4.13.**
- **Notifikasi email pesanan baru** (opsional, `mail()` bawaan cPanel — tanpa dependensi; kolom "Email
  Notifikasi" di Admin → Pengaturan; kegagalan email tidak pernah menggagalkan pesanan).
- **Consent analitik**: GA4/Clarity hanya dimuat setelah persetujuan (banner + cookie `bwn_consent`);
  tanpa ID dikonfigurasi = nol script pihak ketiga (default, UU PDP/GDPR).
- **Redirect hash → clean URL**: URL lama era Next.js `#/{locale}/{slug}` dipetakan ke URL bersih di `app.js`.
- Guard CLI untuk `tools/*.php` (blokir eksekusi via web).
- Dokumentasi: `docs/DEPLOYMENT-CPANEL.md` (tanpa SSH), `docs/SECURITY.md`, `docs/MODERNIZATION-REPORT-2026-11-01.md`.

### Verified
- `php -l` PHP 8.4.13: seluruh file PHP bersih.
- E2E `tests/e2e.sh`: **83/83 PASS**.
- Smoke Apache `.htaccess` teruji struktural (mod_rewrite/FilesMatch/mod_headers/mod_expires bersyarat IfModule — aman di shared hosting).

Riwayat 0.1.0–0.2.1 direkonstruksi jujur dari `worklog.md` (Z3 Version Archaeology).

## [0.5.2] — 2026-10-31 — "Dewan Audit" (Audit Menyeluruh + Perbaikan Nyata)

Sesi audit arah 3.0.0.0 (Dewan-46): seluruh repo diaudit, PHP CMS diuji E2E di PHP 8.4.13 sungguhan, dan 4 temuan diperbaiki langsung.

### Fixed (php-cms/)
- **Override artikel kini benar-benar tampil di halaman publik** — sebelumnya tabel `article_overrides` hanya ditulis Admin CMS, tidak pernah dibaca `render_article()` (perubahan judul/deskripsi/catatan isi tidak berdampak). Kini judul & deskripsi SEO override dipakai di meta + JSON-LD, catatan isi tampil sebagai kartu "Catatan dari kami".
- **Form artikel admin mengambil nilai override tersimpan** — sebelumnya selalu menampilkan data asli JSON, sehingga simpanan kedua menimpa override sebelumnya tanpa sengaja.

### Security (php-cms/)
- **Rate limit login admin berbasis IP** (5 percobaan / 5 menit) — brake berbasis sesi saja dapat dilewati dengan membuang cookie.
- **`rate_limited()` memakai `REMOTE_ADDR`** — sebelumnya memprioritaskan `HTTP_X_FORWARDED_FOR` yang dikirim klien dan mudah dipalsukan untuk melewati rate limit.
- **Petunjuk password default di halaman login hanya tampil selama password default masih aktif** — setelah diganti, petunjuk hilang (tidak lagi menampilkan kredensial bawaan ke publik).

### Changed
- Statistik "Halaman Publik" di dashboard admin dihitung dinamis (tidak lagi hardcode 2644; aktual 2.649).
- Dokumentasi audit lengkap: `AUDIT-2026-10-31.md` (kondisi repo, peta Next.js, rencana migrasi P0/P1/P2 menuju 3.0.0.0).

### Verified (G6)
- `php -l` PHP 8.4.13: seluruh 14 file PHP bersih.
- E2E `php -S` (skrip otomatis): 32+ pemeriksaan — rute publik, SEO endpoint, proteksi file sensitif (403), 404, login admin salah/benar, form kontak dengan/tanpa CSRF, persistensi SQLite.

## [0.5.1] — 2026-10-30 — "Pintu Pencari" (Verifikasi & Analitik PHP CMS)

Kelanjutan SEO Universalis: melengkapi tiga kekosongan yang ditemukan audit permintaan pemilik (sitemap & index sitemap ternyata sudah ada sejak 0.5.0).

### Added (php-cms/)
- **Verifikasi metode FILE**: rute dinamis `/google{kode}.html` (Google Search Console), `/yandex{kode}.html` (Yandex), `/BingSiteAuth.xml` (Bing) — hanya melayani nama file yang terdaftar persis di `config.php` (kode asing → 404, aman). Konten sesuai ketentuan resmi masing-masing mesin.
- **Analitik GA4 + Microsoft Clarity**: konstanta `ANALYTICS_GA4_ID` / `ANALYTICS_CLARITY_ID` (getenv-overridable untuk cPanel `.htaccess SetEnv`); gtag.js dengan `anonymize_ip: true` (UU PDP/GDPR-ready); kosong = nol script dirender (privasi-utama, konsisten dengan mesin Next.js).
- **`PANDUAN-GSC-ANALYTICS.md`**: panduan 15–20 menit — GSC (domain/URL-prefix, verifikasi meta & file, submit sitemap index), Bing Webmaster (impor GSC, BingSiteAuth.xml, IndexNow otomatis), Yandex, GA4 step-by-step, Clarity, + checklist 9 item berstatus.

### Verified (G6)
- `php -l` config.php / index.php / layout.php → bersih.
- Server uji env-gated: `/google1a2b3c4d.html` (diisi via env) → 200 `google-site-verification: …`; kode asing → 404; `/BingSiteAuth.xml` 200; GA4+Clarity script muncul hanya saat ID diisi, nol script saat kosong.

## [0.5.0] — 2025-12-19 — "SEO Universalis" (Mesin Pencari Semua, Termasuk AI)

Aktivasi Dewan-46 Divisi SEO: full SEO untuk Google, Bing, Yandex, DuckDuckGo + mesin pencari AI — di seluruh `php-cms/`.

### Added
- **Tag Hub 1.386 halaman** (`/tag` + `/tag/{slug}`): bank kata kunci pencarian populer (kota besar × pola jual/harga/supplier/distributor, kondisi kesehatan, layanan, bambu, edukasi) — hub agregasi konten nyata, meta & intro dirotasi anti-duplikat; generator `tools/build-keywords.php` (Z2: data JSON tahan lama).
- **`lib/seo.php`**: pencocokan token hub↔konten, breadcrumb/FAQ/Product LD, IndexNow ping, konversi tanggal Indonesia→ISO 8601.
- **AI-SEO**: `/llms.txt` + `/llms-full.txt` (standar llmstxt.org — ringkasan, fakta kunci, katalog, artikel penuh); `robots.txt` matriks **26 user-agent** termasuk GPTBot, OAI-SearchBot, ClaudeBot, PerplexityBot, Google-Extended, dkk.
- **IndexNow (Bing instant indexing)**: kunci verifikasi `/{kunci}.txt`, ping otomatis saat admin menyimpan artikel, alat massal `tools/ping-indexnow.php` (sekali POST → Bing/Yandex/Seznam/Naver).
- **`<head>` penuh**: robots/googlebot/bingbot, hreflang id/en/x-default, Twitter Card, og:url + og:locale + dimensi gambar, article:published/modified_time, meta keywords per halaman, slot verifikasi GSC/Bing/Yandex, RSS `<link>`, geo-tag ID.
- **JSON-LD**: BreadcrumbList di SEMUA halaman; FAQPage (kondisi, layanan, bambu, artikel); Product + AggregateOffer IDR (bambu); BlogPosting diperkaya (datePublished/Modified ISO, inLanguage, publisher).
- **Sitemap index**: `sitemap.xml` → `sitemap-pages.xml` (2.649) + `sitemap-tags.xml` (1.386) = **4.035 URL**, dengan lastmod/changefreq/priority berjenjang.
- **RSS `/feed.xml`** (7 artikel) + **`.htaccess`**: X-Robots-Tag noindex untuk /admin, nosniff, Referrer-Policy, charset.

### Verified (G6)
- `php -l` seluruh file → bersih; 11 rute inti + tag hub + llms + feed + sitemap + key file → 200.
- Matriks JSON-LD via curl: breadcrumb 6/6 tipe halaman; FAQPage 3 tipe; Product 1/1 bambu; `Juni 2025` → `2025-06-01T00:00:00+07:00`.
- Browser: tag hub render 12 kartu terkait + 10 pill topik (bukti `research/seo-tag-hub-v050.png`).

### Catatan jujur Dewan-46
Pondasi teknis kini sempurna & lengkap. Peringkat #1 Google juga ditentukan otoritas (backlink, usia, konten vs kompetitor) — dipandu di bagian "SEO Universalis" README: GSC → Bing Webmaster (impor GSC) → Yandex → IndexNow.

## [0.4.1] — 2025-12-19 — "Kembar Abadi: Push GunaraCorp"

Patch sanitasi publikasi + perbaikan tampilan, dipicu permintaan push repo publik `github.com/gunaracorp/bambu-water`.

### Fixed
- **Title dobel** halaman programatik PHP (`…| Bambu Water Nusantara | Bambu Water Nusantara`): brand kini ditambahkan **tepat sekali** di `templates/layout.php` (guard `stripos` anti-dobel); template kota/kondisi/bambu memberi title polos.

### Changed
- `php-cms/README.md`: struktur `admin/` → `admin-cms.php` (akurat), jumlah URL 2.644 → **2.649** (sesuai sitemap aktual), heading v0.4.1.

### Security (publikasi)
- Repo publik disanitasi dari history: `.env`, `db/custom.db` (PII lead), `php-cms/data/cms.sqlite`, `dev.pid`, paket ZIP → di-untrack + `.gitignore`; SQLite CMS kini di-boot-otomatis oleh `lib/db.php` (aman dihapus).
- Riwayat git diganti satu commit bersih (orphan) — jejak asli tetap terawetkan di `github.com/prabudanling/bambu-water`.

### Verified (G6)
- `php -l` 29 file PHP → semua bersih (PHP 8.3.13 statis).
- Smoke test `php -S :8080`: `/`, `/kota`, `/kota/bandung`, `/air-bambu-untuk/kesehatan-ginjal`, `/layanan/langganan-rumah-tangga`, `/bambu/petung`, `/artikel/*`, `/admin`, `/robots.txt` → 200; 404 ramah untuk slug tak dikenal; sitemap 2.649 URL; canonical + JSON-LD + WA float + badge versi terverifikasi.

## [0.4.0] — 2025-12-18 — "Kembar Abadi" (PHP Full CMS Export)

Sesi lanjutan atas permintaan pemilik: export seluruh situs menjadi **PHP Full CMS** — desain identik, konten 100% dipertahankan (Z2), plus kemampuan baru.

### Added
- **`php-cms/`** — PHP 8 murni + SQLite, tanpa Node.js/build tool, siap shared hosting cPanel:
  - Router bersih (`index.php` front controller) — URL paritas Next.js (`/kota/{slug}`, `/air-bambu-untuk/{slug}`, `/layanan/{slug}`, `/bambu/{slug}`, `/artikel/{slug}`)
  - **2.649 URL** (`sitemap.xml` dinamis): 25 inti + 2.524 kota + 60 kondisi + 25 layanan + 8 bambu + 7 artikel
  - **Admin CMS** (`/admin`): login `password_hash` + brake anti-bruteforce, dashboard statistik, pesan masuk (tandai/hapus), pengaturan situs (nama/WA/email/URL), editor artikel (override aman di DB), ganti password
  - Keamanan: PDO prepared statements, escaping penuh, CSRF, rate limit form 5/menit, blokir unduh `data/` (403)
  - Fitur interaktif: command palette Ctrl+K (indeks 2.649), kalkulator kesehatan, lacak pesanan, filter live
  - Data 1:1 dari `src/lib/data/*.ts` → JSON via bun (Z2: Content Outlives Code)
- Paket distribusi: `bambu-water-php-cms-v0.4.0.zip` (36 file, 1,3 MB)

### Verified (G6)
- `php -l` semua file bersih; runtime `php -S :3001` — 20+ rute 200; form kontak POST → 302 `?ok=1` → tersimpan SQLite; admin login → dashboard/pesan/pengaturan 200; sitemap 2.649 URL; rate-limit 429; screenshot browser: beranda/kota/admin (`research/php-*.png`)

### Notes
- i18n EN parsial (fallback ID) — pola sama dengan Next.js
- Lacak pesanan = simulasi deterministik (paritas dengan versi Next.js)

## [0.3.0] — 2025-12-17 — "Transplantasi Abadi"

Sesi GOD-MODE v3.0 (MPS-46): transplantasi repo GitHub → sandbox produksi + standarisasi "Abadi-Standard 12".

### Added
- **S1 Sistem Versi**: `src/lib/version.ts` (sumber tunggal), `CHANGELOG.md`, badge versi di footer, endpoint publik `GET /api/version`, field `softwareVersion` pada JSON-LD WebSite.
- **S6 Keamanan**: rate-limit in-memory `POST /api/contact` (5 req/menit/IP → HTTP 429) + security headers global (`nosniff`, `Referrer-Policy`, `Permissions-Policy`, DNS-prefetch) via `next.config.ts`.
- **S7 Analitik**: `Analytics` env-gated (GA4 `NEXT_PUBLIC_GA_ID` + Microsoft Clarity `NEXT_PUBLIC_CLARITY_ID`) — nol script saat env kosong (Z5).
- **S12 Resiliensi**: `GET /api/health` (probe DB + versi + latensi) dan `RUNBOOK.md` (restart, rollback, backup, recovery).
- **S10 Aksesibilitas**: skip-link "Langsung ke konten" menuju `#konten-utama`.
- **STATE.md**: memori ganda sesi AI (identitas, skor 12 area, kapabilitas tegak, NEXT-QUEUE, PIAGAM 2125, PETA MODUL ABADI).

### Changed
- Transplantasi penuh `src/`, `public/`, `prisma/`, `db/`, `scripts/` dari github.com/prabudanling/bambu-water ke runtime sandbox (FUSE RESURRECTION — kehilangan server ≠ kehilangan brand).
- README: badge versi + skor.

### Verified
- `GET /` 200 · sitemap.xml **2.654 URL** · robots.txt · manifest.webmanifest · `/api/contact` GET/POST · `/api/version` · `/api/health` — semua via curl.
- E2E agent-browser: beranda, hash-route, form kontak, footer badge (laporan FASE 5).

## [0.2.1] — 2025-12-16 — "33 Halaman + Motion Sinematik" (rekonstruksi)

### Added
- 8 halaman inti baru: kalkulator-kesehatan (3in1), peta-situs (2.654 link), glossarium (41 istilah), perbandingan, lacak-pesanan, karier, kebijakan-privasi, syarat-ketentuan.
- Command Palette Ctrl/Cmd+K (indeks 2.654+ halaman), motion system GPU (aurora, blur-in, magnetic), PWA manifest, ikon dinamis next/og, robots granular multi-bot, JSON-LD HowTo/JobPosting/WebApplication/DefinedTermSet.
- Fix kritis: crash 7 halaman artikel (`ARTICLE_UI_FALLBACK`).

## [0.2.0] — 2025-12-16 — "Mesin Programatik + i18n 30" (rekonstruksi)

### Added
- Auto-Pages Engine: 2.524 kota, 60 kondisi, 25 layanan, 8 bambu = 2.621 halaman programatik; hash-routing hydration-proof; i18n 30 bahasa RTL; sitemap 2.654 URL.

## [0.1.0] — 2025-12-15 — "Kelahiran Digital" (rekonstruksi)

### Added
- Situs awal 1 route + 14 section animasi: hero, stats, benefits, science, process, products, delivery, leadership (data akta), testimonials, FAQ, contact form (Prisma ContactMessage), footer, WhatsApp float; 8 gambar AI; JSON-LD Corporation/WebSite/LocalBusiness/Product/FAQPage.