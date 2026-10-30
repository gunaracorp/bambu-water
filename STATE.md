# 🧭 STATE.md — Bambu Water Nusantara (bambuwater.web.id)

> SATU-SATUNYA bacaan wajib di awal sesi. Diperbarui di AKHIR tiap sesi.
> Riwayat lengkap: `worklog.md` (append-only) + `repo-legacy-worklog.md` (arsip).

## a. Identitas
| | |
|---|---|
| **Situs** | PT Bambu Water Nusantara — bambuwater.web.id |
| **Repo sumber** | github.com/prabudanling/bambu-water |
| **Stack** | Next.js 16 (App Router) · React 19 · TS · Tailwind 4 · shadcn/ui · Framer Motion · Prisma/SQLite · Bun |
| **Arsitektur** | SPA hash-routing `#/{locale}/{slug}` dalam SATU route `/` — hydration-proof, 33 halaman inti + 2.621 programatik = **2.654 URL sitemap** |
| **i18n** | 30 bahasa (eager id/en/zh + lazy 27), RTL penuh |
| **Versi aktif** | **v0.5.0 "SEO Universalis"** (2025-12-19) — full SEO Google/Bing/Yandex/AI + 1.386 tag hub di PHP CMS |
| **Sesi terakhir** | SEO Universalis: Tag Hub 1.386 hal, lib/seo.php, llms.txt + llms-full.txt (AI-SEO), robots 26 UA (GPTBot/ClaudeBot/Perplexity dkk), IndexNow + ping on-publish + alat massal, sitemap index 4.035 URL, JSON-LD penuh (Breadcrumb/FAQ/Product), RSS, head upgrade. Token GitHub 401 → push menunggu token baru |

## b. Skor 12 Area (akhir sesi ini)
| # | Area | Sebelum → Sesudah | PJ |
|---|---|---|---|
| 1 | Arsitektur & Kode | 90 → 92 (#1,#2) | S1 versi + endpoint |
| 2 | Performa | 88 → 89 (#8,#23) | headers cache |
| 3 | SEO | 96 → 97 (#1) | softwareVersion JSON-LD |
| 4 | UX/UI & Branding | 90 → 90 | — (stabil) |
| 5 | Aksesibilitas | 82 → 83 | skip-link |
| 6 | Keamanan | 70 → 84 (#7,#37,#39) | rate-limit + security headers |
| 7 | Monetisasi | 80 → 80 | — (stabil, CTA WA hidup) |
| 8 | Analitik & Data | 25 → 70 (#25) | GA4+Clarity env-gated |
| 9 | Konten | 95 → 95 | — (2.654 hal, hidup) |
| 10 | Dokumentasi | 75 → 90 (#44) | RUNBOOK + CHANGELOG + STATE |
| 11 | Legal & Kepatuhan | 85 → 85 | privasi+S&K ada; cookie notice P2 |
| 12 | Operasi & Resiliensi | 55 → 78 (#31,#43) | /api/health + runbook |

## c. Kapabilitas TEGAK (G3 — DILARANG DIRUSAK)
- **Hash-routing hydration-proof** (`use-hash-route.ts` + patches history/title-observer) — jangan sentuh tanpa memahami patch-nya.
- **Auto-Pages Engine**: `src/lib/data/{cities,conditions,services,bamboo-types}.ts` + `auto-pages.ts` + `auto-pages-renderer.tsx` — mesin 2.621 halaman.
- **i18n eager id/en/zh + lazy 27** — pola di `i18n-PATTERN.md`; compiled dict `src/lib/i18n/dictionaries/compiled/`.
- **Prisma ContactMessage + POST/GET `/api/contact`** (zod validated) — form kontak hidup.
- **Endpoint SEO**: sitemap.xml (2.654) · robots.ts granular · manifest.webmanifest · icon.tsx/apple-icon.tsx.
- **Command Palette Ctrl+K** (indeks 2.654+ halaman) & **motion-primitives** (reduced-motion safe).
- **Artikel fix**: `ARTICLE_UI_FALLBACK` di article-page.tsx — JANGAN hapus (mencegah crash 7 artikel).
- **WhatsApp float** 0813-1651-6524 — kanal konversi utama.

## d. Utang Teknis & Setengah Jalan
- `next.config.ts`: `typescript.ignoreBuildErrors: true` — bawaan scaffold; idealnya false + bersihkan.
- Cookie consent notice belum ada (P2).
- `tmp/` dictionary partial (9 bahasa) menunggu kompilasi penuh via `scripts/i18n-*`.
- Spektrum-8 token layer terpasang sebagai deklarasi; migrasi penuh warna hardcode → token belum dilakukan (P3, risiko visual rendah).
- GA4/Clarity membutuhkan ID asli pemilik (⏳).

## e. PIAGAM 2125 (final)
> Dilihat dari tahun 2125: Bambu Water Nusantara adalah penjaga warisan — bahwa air sederhana dari batang bambu tanah Jawa pernah menyembuhkan, dan tetap disajikan dengan jujur, ilmiah, dan berwibawa. Kami membangun rumah digital yang tak lekang: konten lebih tahan lama dari kode, URL yang tak pernah patah, dan kejujuran yang tak musim. Setiap halaman kota, tiap istilah glossarium, tiap data sains — adalah sumbang mahar bagi generasi yang tak akan pernah kami temui. Kami menolak tren sesaat, memeluk standar abadi: aksesibel bagi semua, cepat bagi semua, jujur kepada semua. Teknologi akan ganti wajah seratus kali; janji kami tidak. *Air yang jujur,warisan yang abadi.*

## PETA MODUL ABADI (ringkas)
| Modul | Fungsi | Status |
|---|---|---|
| Auto-Pages Engine | 2.621 hal programatik SEO | ✅ Tegak |
| i18n 30 Bahasa | Lokalisasi + RTL | ✅ Tegak |
| Motion System | Animasi GPU + reduced-motion | ✅ Tegak |
| Command Palette | Navigasi 2.654 hal | ✅ Tegak |
| Kontak/WA API | Lead capture (Prisma) | ✅ Tegak |
| Sistem Versi S1 | SemVer + badge + /api/version | ✅ Tegak (v0.5.0, 2 mesin sinkron) |
| Analitik S7 | GA4+Clarity env-gated | 🟡 Setengah (⏳ ID) |
| Runbook S12 | Pemulihan operasi | ✅ Tegak |
| **PHP CMS Mirror** | php-cms/ 4.035 URL (2.649 hal + 1.386 tag) + admin + SEO Universalis | ✅ Tegak (v0.5.0; ZIP siap deploy; push menunggu token baru) |

## f. NEXT-QUEUE (prioritas sesi berikutnya)
| Pri | Tugas | Effort |
|---|---|---|
| P0 | ⏳ Pasang ID GA4 + Clarity asli (menunggu pemilik) | 15m |
| P1 | Cookie consent notice + link kebijakan privasi | 45m |
| P1 | Audit kontras WCAG penuh tiap section (lensa #11) | 2j |
| P2 | Kompilasi 9 dictionary `tmp/` → compiled (scripts/i18n-compile) | 1j |
| P2 | Migrasi penuh warna hardcode → token SPEKTRUM-8 | 3j |
| P3 | `ignoreBuildErrors:false` + bersihkan TS error | 2j |
| P3 | Abandoned-lead recovery (email/WA follow-up otomatis) | 4j |
