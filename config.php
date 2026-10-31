<?php
/**
 * Bambu Water Nusantara — PHP 8.4 + SQLite, arsitektur PHP Murni
 * Konfigurasi pusat. Satu-satunya file yang wajib disentuh saat pindah hosting.
 * Aplikasi PHP murni untuk shared hosting cPanel — tanpa Node.js, tanpa build step.
 */
declare(strict_types=1);

define('APP_NAME', 'Bambu Water Nusantara');
define('APP_VERSION', '3.0.0.0');
define('APP_CODENAME', 'Nusantara PHP Murni');
define('SITE_URL', 'https://bambuwater.web.id'); // ganti saat deploy, mis. https://bambuwater.web.id
define('BASE_PATH', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/'));
define('ROOT_DIR', __DIR__);

/* --- Penyimpanan privat ---
   Default: folder data/ di dalam aplikasi (dilindungi .htaccess berlapis).
   cPanel TINGKAT LANJUT: pindahkan di luar public_html dengan membuat folder
   mis. /home/USER/bwn-data lalu set env BWN_DATA_DIR (atau FilesMatch tetap
   melindungi lokasi default — dua-duanya aman). */
define('DATA_DIR', (string) (getenv('BWN_DATA_DIR') ?: ROOT_DIR . '/data'));
define('DB_FILE', DATA_DIR . '/cms.sqlite');
define('CACHE_DIR', DATA_DIR . '/cache');

/* --- Hardening sesi PHP (diterapkan sebelum session_start mana pun) --- */
if (PHP_SAPI !== 'cli' && session_status() === PHP_SESSION_NONE) {
    $bwnIsHttps = (($_SERVER['HTTPS'] ?? '') !== '' && ($_SERVER['HTTPS'] ?? '') !== 'off')
        || (($_SERVER['REQUEST_SCHEME'] ?? '') === 'https')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Lax');
    ini_set('session.cookie_secure', $bwnIsHttps ? '1' : '0');
    ini_set('session.cookie_lifetime', '0');
    ini_set('session.gc_maxlifetime', '7200');
    session_name('BWNSESS');
    unset($bwnIsHttps);
}

/* --- Notifikasi email pesanan baru (opsional) ---
   Isi alamat tujuan di Admin → Pengaturan (kolom "Email Notifikasi") atau
   set env BWN_NOTIFY_EMAIL. Kosong = tanpa email (pesan tetap masuk database). */
define('CONTACT_NOTIFY_EMAIL', (string) (getenv('BWN_NOTIFY_EMAIL') ?: ''));

/* --- Kontak & brand (bisa dioverride dari Admin → Pengaturan) --- */
define('DEFAULT_PHONE', '+62 813-1651-6524');
define('DEFAULT_WA', '6281316516524');
define('DEFAULT_EMAIL', 'halo@bambuwater.web.id');

/* --- Admin CMS (G6: ubah password pertama kali login!) --- */
define('ADMIN_USER', 'admin');
define('ADMIN_DEFAULT_PASS', 'bambuwater2025'); // di-hash otomatis saat login pertama — WAJIB diganti
/* Ganti password default ditegakkan: admin diarahkan ke menu Akun selama
   password default masih aktif (lihat admin-cms.php). */

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
