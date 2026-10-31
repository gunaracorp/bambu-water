# 📡 Panduan Google Search Console & Analytics — PHP CMS Bambu Water

> Versi dokumen: v0.5.1 "Pintu Pencari" (2026-10-30)
> Target: situs PHP mirror (./) di bambuwater.web.id
> Estimasi total: ± 15–20 menit untuk semua platform di bawah.

---

## 1. Google Search Console (WAJIB — prioritas #1)

### 1a. Buat Properti
1. Buka https://search.google.com/search-console → login akun Google pemilik domain.
2. Pilih **"Add Property"** → tipe **Domain** (rekomendasi, mencakup semua subdomain + protokol) → verifikasi via **DNS TXT** di pengelola domain; **atau** tipe **URL Prefix** `https://bambuwater.web.id` → verifikasi via meta/file (langkah 1b).

### 1b. Verifikasi — DUA metode sudah siap di CMS ini

**Metode A — Meta tag (paling cepat):**
1. Di GSC pilih metode *HTML tag* → salin `content="…"` di antara tanda kutip.
2. Edit `config.php`:
   ```php
   define('SEO_GOOGLE_SITE_VERIFICATION', 'TEMPEL_KODE_DI_SINI');
   ```
3. Upload, lalu cek: `curl -s https://bambuwater.web.id | grep google-site-verification` → meta muncul di `<head>` setiap halaman.

**Metode B — File HTML (tanpa menyentuh tema):**
1. Di GSC pilih metode *HTML file* → unduh file, mis. `google1a2b3c4d.html`.
2. Edit `config.php` (cukup nama filenya, TANPA upload file apa pun):
   ```php
   define('SEO_GSC_VERIFY_FILE', 'google1a2b3c4d.html');
   ```
3. CMS otomatis menyajikan `https://bambuwater.web.id/google1a2b3c4d.html` berisi persis `google-site-verification: google1a2b3c4d.html` (konten wajib Google).
4. Klik *Verify* di GSC. ✅

> Alternatif tanpa edit file: `SetEnv SEO_GSC_VERIFY_FILE google1a2b3c4d.html` di `.htaccess`.

### 1c. Submit Sitemap (penting — 4.035 URL)
Dashboard GSC → menu **Sitemaps** → submit **satu** ini (sudah berformat index):
```
https://bambuwater.web.id/sitemap.xml
```
Isi index otomatis: `sitemap-pages.xml` (2.649 halaman) + `sitemap-tags.xml` (1.386 tag kata kunci). Tidak perlu submit keduanya manual — Google mengikuti index.

### 1d. Indexing Instan (opsional tapi disarankan)
- CMS sudah siap **IndexNow**: kunci terdaftar di `config.php` (`INDEXNOW_KEY`), file verifikasi `/{kunci}.txt` otomatis hidup, dan setiap artikel yang disimpan via admin otomatis di-ping.
- Untuk ping massal sekali saja: `php tools/ping-indexnow.php`.
- Di GSC juga bisa pakai *URL Inspection → Request Indexing* untuk halaman prioritas.

---

## 2. Bing Webmaster Tools (penting — mesin #2)

1. Buka https://www.bing.com/webmasters → **"Import from Google Search Console"** (30 detik, properti + sitemap ikut terbawa), atau daftar manual.
2. Jika diminta verifikasi — dua cara siap:
   - **Meta:** `define('SEO_BING_SITE_VERIFICATION', 'KODE_MSVALIDATE_01');`
   - **File:** tempel seluruh isi `BingSiteAuth.xml` dari dashboard ke:
     ```php
     define('SEO_BING_AUTH_XML', '<?xml version="1.0"?><users>…</users>');
     ```
     CMS menyajikannya di `https://bambuwater.web.id/BingSiteAuth.xml`.
3. Sitemap terkirim otomatis bila impor; manual: Sitemaps → submit `https://bambuwater.web.id/sitemap.xml`.
4. **IndexNow langsung aktif** di Bing (kunci sama, otomatis).

---

## 3. Yandex Webmaster (opsional — pasar RU/AI)

1. Buka https://webmaster.yandex.com → tambahkan situs.
2. Verifikasi:
   - **Meta:** `define('SEO_YANDEX_VERIFICATION', 'KODE_YANDEX');`
   - **File:** `define('SEO_YANDEX_VERIFY_FILE', 'yandex9f8e7dab.html');` → CMS menyajikan file tersebut dengan konten verifikasi standar.
3. Sitemap: Indexing → Sitemap files → `https://bambuwater.web.id/sitemap.xml`.
4. Daftarkan situs juga di https://cleantalk.org? Tidak — cukup Yandex; robot.txt CMS sudah mengizinkan YandexBot dengan Crawl-delay wajar.

---

## 4. Google Analytics 4 (analitik trafik)

1. https://analytics.google.com → Admin → **Create Property** (nama: "Bambu Water Nusantara", zona waktu Jakarta, mata uang IDR).
2. Property → **Data Streams → Web** → masukkan `https://bambuwater.web.id` → salin **Measurement ID** berformat `G-XXXXXXXXXX`.
3. Edit `config.php`:
   ```php
   define('ANALYTICS_GA4_ID', 'G-XXXXXXXXXX');
   ```
4. Upload → buka halaman apa pun → *View Source* → pastikan `googletagmanager.com/gtag/js?id=G-…` ada sebelum `</body>`.
5. GA4 → Reports → Realtime → buka situs di tab lain; sesi muncul < 1 menit. ✅
6. Privasi: CMS mengaktifkan `anonymize_ip` otomatis (kepatuhan UU PDP/GDPR). Saran: aktifkan *Google Signals* saja bila kebijakan cookie situs sudah disetujui pemilik.

## 5. Microsoft Clarity (heatmap + rekaman sesi, gratis)

1. https://clarity.microsoft.com → New Project → masukkan `bambuwater.web.id`.
2. Salin **Project ID** → `config.php`:
   ```php
   define('ANALYTICS_CLARITY_ID', 'ID_PROYEK_CLARITY');
   ```
3. Upload → Realtime dashboard Clarity menunjukkan pengunjung. ✅

> Keduanya (GA4 & Clarity) bisa juga diisi lewat env tanpa edit file:
> `SetEnv ANALYTICS_GA4_ID G-XXXXXXXXXX` (.htaccess).

---

## 6. Checklist Akhir (semua rute CMS sudah terbukti 200)

| Item | URL | Status |
|---|---|---|
| Sitemap index | `/sitemap.xml` | ✅ dinamis, 2 sub-sitemap |
| Sub-sitemap halaman | `/sitemap-pages.xml` | ✅ 2.649 URL |
| Sub-sitemap tag | `/sitemap-tags.xml` | ✅ 1.386 URL |
| RSS | `/feed.xml` | ✅ |
| robots.txt (26 UA + AI bots) | `/robots.txt` | ✅ |
| llms.txt (AI-SEO) | `/llms.txt` · `/llms-full.txt` | ✅ |
| File verifikasi GSC/Bing/Yandex | `/google…html` · `/BingSiteAuth.xml` · `/yandex…html` | ✅ hidup begitu diisi |
| IndexNow key | `/{kunci}.txt` | ✅ otomatis |
| GA4 + Clarity | sebelum `</body>` | ✅ hidup begitu diisi |

**Urutan eksekusi yang disarankan:** GSC (1) → sitemap (1c) → Bing impor (2) → GA4 (4) → Clarity (5) → Yandex (3, senggang).
