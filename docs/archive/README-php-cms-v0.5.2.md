# 🌿 Bambu Water Nusantara — PHP Full CMS v0.4.1 "Kembar Abadi"

Mirror penuh situs Next.js bambuwater.web.id ke **PHP murni + SQLite** — desain identik,
URL bersih, plus **panel CMS admin**. Tanpa Node.js, tanpa build tool — siap shared
hosting cPanel mana pun.

## ✨ Isi Paket
| Komponen | Detail |
|---|---|
| **Halaman publik** | Beranda penuh (14 section) + 24 halaman inti + **2.524 kota** + 60 kondisi kesehatan + 25 layanan + 8 jenis bambu + 7 artikel = **2.649 URL** |
| **Admin CMS** | `/admin` — dashboard, pesan masuk (buka/hapus/tandai), pengaturan situs (nama, WA, email, URL), editor artikel (override aman), ganti password |
| **SEO** | Meta unik per URL (server-side!), canonical + OG + JSON-LD, `sitemap.xml` dinamis 2.649 URL, robots.txt, 404 ramah |
| **Keamanan** | PDO prepared statements, `htmlspecialchars` penuh, CSRF token, rate limit form 5/menit, password_hash, brake login, blokir akses `data/` |
| **Fitur interaktif** | Command palette Ctrl+K (indeks 2.649 halaman), kalkulator kesehatan 3-in-1, lacak pesanan, filter live daftar kota/istilah, reveal-on-scroll, tab produk |
| **i18n** | ID penuh + EN parsial (tumbler `?lang=en`), arsitektur siap nambah bahasa |

## 🚀 Instalasi Shared Hosting (cPanel)
1. Upload seluruh folder ini (atau paket ZIP rilis `bambu-water-php-cms-v*.zip`) ke `public_html/` — atau clone repo ini dan ambil folder `php-cms/`.
2. Pastikan folder `data/` **writable** (chmod 755/775) — SQLite `cms.sqlite` dibuat otomatis saat request pertama.
3. Selesai. Buka domain Anda; admin di `domain.com/admin`.
4. **Login pertama:** `admin` / `bambuwater2025` → menu **Akun** → ganti password (wajib!).
5. Menu **Pengaturan** → isi URL domain asli (untuk canonical & sitemap).

## 🖥️ Jalankan Lokal (tanpa Apache)
```bash
php -S 0.0.0.0:8080 index.php
# buka http://localhost:8080
```

## 🧩 Struktur
```
php-cms/
├── index.php          # Front controller + router bersih
├── config.php         # ⭐ Satu file konfigurasi (versi, admin, kontak default)
├── lib/               # helpers (escape/CSRF/rate-limit/i18n) + db (PDO SQLite)
├── data/              # Konten JSON (2.524 kota dll) + cms.sqlite (dibuat otomatis)
├── templates/         # layout, pages (inti), programmatic, sitemap, contact-submit
├── admin-cms.php      # Panel CMS tunggal (login, pesan, artikel, pengaturan, akun)
├── assets/            # style.css (design system), app.js, images brand
└── .htaccess          # Clean URL + proteksi + cache header
```

## 🔁 Paritas dengan Situs Next.js
- Palet & tipografi sama (hijau bambu `#022c22`, emas `#d4a843`, Playfair + Jakarta Sans)
- Semua slug sama: `/kota/bandung`, `/air-bambu-untuk/ginjal`, `/layanan/{slug}`, `/bambu/petung`, `/artikel/{slug}`
- Data di-export 1:1 dari `src/lib/data/*.ts` → JSON (Z2: konten tak pernah mati)

**Keunggulan tambahan versi PHP:** HTML server-side per URL (SEO murni tanpa hash-routing),
CMS admin, URL bersih, SQLite terpadatkan, dan tanpa dependensi runtime.

## 📌 Catatan Jujur (G6/G8)
- i18n EN parsial (nav/footer/label) — konten programatik fallback ID (pola sama dengan Next.js)
- Lacak pesanan = simulasi deterministik (paritas dengan versi Next.js) — bukan integrasi kurir
- Uji runtime dilakukan dengan `php -S` + browser E2E di sandbox; Apache .htaccess diuji struktural

---

## 🔍 SEO Universalis (v0.5.0) — Panduan Menjadi Acuan Mesin Pencari

Paket SEO terpadu untuk Google, Bing, Yandex, DuckDuckGo, **dan mesin pencari AI** (ChatGPT Search, Perplexity, Claude, Copilot).

### Yang sudah terpasang otomatis
| Lapisan | Isi |
|---|---|
| **Meta head** | Title unik, description, canonical, `robots` + `googlebot` + `bingbot`, hreflang (id/en/x-default), OG lengkap + dimensi gambar, **Twitter Card**, geo-tag ID, `article:published_time` (konversi otomatis tanggal Indonesia → ISO 8601) |
| **JSON-LD** | Organization (global) · **BreadcrumbList** (semua halaman) · **FAQPage** (kondisi, layanan, bambu, artikel) · **Product + AggregateOffer IDR** (halaman bambu) · BlogPosting (artikel) |
| **Sitemap** | `sitemap.xml` = index → `sitemap-pages.xml` (2.649 URL, prioritas berjenjang) + `sitemap-tags.xml` (1.386 tag) dengan `lastmod`/`changefreq` |
| **robots.txt** | Matriks eksplisit 26 user-agent: mesin pencari utama + **AI crawler** (GPTBot, OAI-SearchBot, ClaudeBot, PerplexityBot, dkk.) |
| **AI-SEO** | `/llms.txt` (ringkasan + tautan kunci) & `/llms-full.txt` (katalog + artikel penuh) — standar [llmstxt.org] agar AI mengutip situs Anda dengan benar |
| **Tag Hub** | `/tag` + 1.386 halaman `/tag/{slug}` — HUB agregasi konten nyata (bukan doorway), meta & intro dirotasi anti-duplikat |
| **IndexNow** | File kunci `/{kunci}.txt`, ping otomatis saat admin menyimpan artikel, alat submit massal |
| **RSS** | `/feed.xml` — 7 artikel, sinyal kesegaran untuk Bing/DuckDuckGo/Feedly |
| **Teknis** | Kompresi gzip, cache header, charset UTF-8, admin `noindex` via `X-Robots-Tag` |

### Langkah Anda sekali jalan (15 menit — wajib untuk peringkat)
1. **Google Search Console** → [search.google.com/search-console](https://search.google.com/search-console) → daftarkan properti domain → salin kode verifikasi → tempel ke `SEO_GOOGLE_SITE_VERIFICATION` di `config.php` → submit `sitemap.xml`.
2. **Bing Webmaster Tools** → [bing.com/webmasters](https://www.bing.com/webmasters) → verifikasi (kode → `SEO_BING_SITE_VERIFICATION`) → **impor dari GSC sekali klik** → submit sitemap.
3. **Yandex Webmaster** → [webmaster.yandex.com](https://webmaster.yandex.com) → verifikasi → `SEO_YANDEX_VERIFICATION`.
4. **IndexNow (instant Bing)** → dari folder php-cms jalankan:
   ```bash
   php tools/ping-indexnow.php https://domain-anda.com
   ```
   Sejak itu, setiap artikel yang Anda simpan di Admin CMS otomatis di-ping ke Bing/Yandex/Seznam/Naver.
5. **Google Analytics 4 / Clarity** (opsional, pemantauan): pasang di situs Next.js via env — mirror PHP fokus ke indexing.

### Ekspektasi jujur Dewan-46
Teknis di atas = **pondasi sempurna** (indeks cepat, tanpa error crawl, kaya cuplikan/FAQ/product di SERP, terbaca AI). Peringkat #1 juga ditentukan: kualitas konten vs kompetitor, usia domain, backlink, dan perilaku pengguna — hal yang tumbuh dengan waktu dan promosi (WA, media sosial, marketplace). Kami berikan semua yang di sisi teknis bisa dikendalikan — sisanya strategi konten & otoritas yang terus kami siapkan strukturnya.

