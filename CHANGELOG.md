# Changelog — bambuwater.web.id

Format: [Keep a Changelog](https://keepachangelog.com) · SemVer · **1 tugas = 1 versi** (S1).
Riwayat 0.1.0–0.2.1 direkonstruksi jujur dari `worklog.md` (Z3 Version Archaeology).

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