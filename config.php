<?php
/**
 * Bambu Water Nusantara — PHP Full CMS v0.5.0 "SEO Universalis"
 * Konfigurasi pusat. Satu-satunya file yang wajib disentuh saat pindah hosting.
 * Mirror dari situs Next.js bambuwater.web.id — desain identik, URL bersih.
 */
declare(strict_types=1);

define('APP_NAME', 'Bambu Water Nusantara');
define('APP_VERSION', '0.5.2');
define('APP_CODENAME', 'Dewan Audit (PHP CMS)');
define('SITE_URL', 'https://bambuwater.web.id'); // ganti saat deploy, mis. https://bambuwater.web.id
define('BASE_PATH', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/'));
define('ROOT_DIR', __DIR__);
define('DATA_DIR', ROOT_DIR . '/data');
define('DB_FILE', ROOT_DIR . '/data/cms.sqlite');

/* --- Kontak & brand (bisa dioverride dari Admin → Pengaturan) --- */
define('DEFAULT_PHONE', '+62 813-1651-6524');
define('DEFAULT_WA', '6281316516524');
define('DEFAULT_EMAIL', 'halo@bambuwater.web.id');

/* --- Admin CMS (G6: ubah password pertama kali login!) --- */
define('ADMIN_USER', 'admin');
define('ADMIN_DEFAULT_PASS', 'bambuwater2025'); // di-hash otomatis saat login pertama

/* --- Debug: jangan true di produksi --- */
define('APP_DEBUG', false);

/* --- SEO Universalis (v0.5.0) --- */
/* Verifikasi Google Search Console / Bing Webmaster / Yandex — isi kode meta
   dari dashboard masing-masing (tanpa tanda kutip), kosong = tag tak dicetak. */
define('SEO_GOOGLE_SITE_VERIFICATION', '');
define('SEO_BING_SITE_VERIFICATION', '');
define('SEO_YANDEX_VERIFICATION', '');
define('SEO_TWITTER_HANDLE', '@bambuwater');   // handle X/Twitter brand
/* Kunci IndexNow — instant indexing ke Bing/Yandex/Seznam/Naver.
   File verifikasi otomatis tersedia di /{kunci}.txt (rute index.php). */
define('INDEXNOW_KEY', 'a28b2801c357a006444c582d886099fe');

/* --- Verifikasi metode FILE (v0.5.1) — alternatif meta tag di atas ---
   GSC:    nama file persis dari dashboard, mis. 'google1a2b3c4d.html'
   Yandex: mis. 'yandex9f8e7d.html'
   Bing:   tempel SELURUH isi BingSiteAuth.xml dari dashboard Bing.
   Kosong = rute file tak aktif (404). Bisa juga via env tanpa edit file:
   SetEnv SEO_GSC_VERIFY_FILE googleXXXX.html  (.htaccess cPanel) */
define('SEO_GSC_VERIFY_FILE', getenv('SEO_GSC_VERIFY_FILE') ?: '');
define('SEO_YANDEX_VERIFY_FILE', getenv('SEO_YANDEX_VERIFY_FILE') ?: '');
define('SEO_BING_AUTH_XML', getenv('SEO_BING_AUTH_XML') ?: '');

/* --- Analitik (v0.5.1) — GA4 + Microsoft Clarity ---
   GA4:     'G-XXXXXXXXXX' dari Admin → Data Streams
   Clarity: ID proyek dari dashboard clarity.microsoft.com
   Kosong = NOL script dirender (privasi-utama, UU PDP/GDPR-ready).
   Catatan kepatuhan: pasang setelah kebijakan cookie disepakati pemilik
   (lihat STATE.md NEXT-QUEUE P1). Bisa juga via env tanpa edit file. */
define('ANALYTICS_GA4_ID', getenv('ANALYTICS_GA4_ID') ?: '');
define('ANALYTICS_CLARITY_ID', getenv('ANALYTICS_CLARITY_ID') ?: '');

error_reporting(APP_DEBUG ? E_ALL : 0);
ini_set('display_errors', APP_DEBUG ? '1' : '0');
date_default_timezone_set('Asia/Jakarta');

require ROOT_DIR . '/lib/helpers.php';
require ROOT_DIR . '/lib/db.php';
