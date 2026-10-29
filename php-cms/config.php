<?php
/**
 * Bambu Water Nusantara — PHP Full CMS v0.4.0 "Kembar Abadi"
 * Konfigurasi pusat. Satu-satunya file yang wajib disentuh saat pindah hosting.
 * Mirror dari situs Next.js bambuwater.web.id — desain identik, URL bersih.
 */
declare(strict_types=1);

define('APP_NAME', 'Bambu Water Nusantara');
define('APP_VERSION', '0.4.1');
define('APP_CODENAME', 'Kembar Abadi (PHP CMS)');
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

error_reporting(APP_DEBUG ? E_ALL : 0);
ini_set('display_errors', APP_DEBUG ? '1' : '0');
date_default_timezone_set('Asia/Jakarta');

require ROOT_DIR . '/lib/helpers.php';
require ROOT_DIR . '/lib/db.php';
