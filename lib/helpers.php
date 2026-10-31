<?php
/** Helper global — escaping, CSRF, i18n, data loader, rate limit, WA. */
declare(strict_types=1);

/** Escape output (XSS). Pakai di SEMUA echo. Menerima scalar/array-string apa pun. */
function e(mixed $v): string
{
    return htmlspecialchars((string) $v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function json_load(string $name): array
{
    static $cache = [];
    if (!isset($cache[$name])) {
        $file = DATA_DIR . '/' . $name . '.json';
        $cache[$name] = is_file($file) ? (json_decode((string) file_get_contents($file), true) ?: []) : [];
    }
    return $cache[$name];
}

function company(string $key = '', mixed $default = null): mixed
{
    $c = json_load('company');
    return $key === '' ? $c : ($c[$key] ?? $default);
}

function setting(string $key, string $default = ''): string
{
    static $rows = null;
    if ($rows === null) {
        $rows = [];
        try {
            foreach (db()->query('SELECT k, v FROM settings') as $r) {
                $rows[$r['k']] = $r['v'];
            }
        } catch (Throwable) {
        }
    }
    return trim((string) ($rows[$key] ?? '')) !== '' ? $rows[$key] : $default;
}

function site_url_path(string $path = '/'): string
{
    return BASE_PATH . $path;
}

function wa_link(string $text = ''): string
{
    $num = setting('whatsapp', DEFAULT_WA);
    return 'https://wa.me/' . preg_replace('/\D/', '', $num) . ($text !== '' ? '?text=' . rawurlencode($text) : '');
}

/* ---------- CSRF ---------- */
function csrf_token(): string
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_ok(): bool
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    return isset($_POST['csrf'], $_SESSION['csrf']) && hash_equals($_SESSION['csrf'], (string) $_POST['csrf']);
}

/* ---------- Rate limit (file-based, tanpa infra) ---------- */
function rate_limited(string $bucket, int $max = 5, int $windowSec = 60): bool
{
    /* Keamanan: REMOTE_ADDR diutamakan — HTTP_X_FORWARDED_FOR dikirim klien dan
       mudah dipalsukan untuk melewati rate limit. XFF hanya fallback bila
       REMOTE_ADDR kosong (kasus proxy ekstrem tertentu). */
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    if ($ip === '' || $ip === 'cli') {
        $ip = trim(explode(',', (string) ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? 'cli'))[0]);
    }
    $file = sys_get_temp_dir() . '/bwn_rl_' . md5($bucket . '|' . $ip) . '.json';
    $now = time();
    $data = is_file($file) ? json_decode((string) file_get_contents($file), true) : null;
    if (!is_array($data) || ($data['resetAt'] ?? 0) < $now) {
        $data = ['count' => 0, 'resetAt' => $now + $windowSec];
    }
    $data['count']++;
    @file_put_contents($file, json_encode($data), LOCK_EX);
    return $data['count'] > $max;
}

/* ---------- i18n ringan: id penuh, en parsial, fallback id ---------- */
function lang(): string
{
    $l = $_GET['lang'] ?? ($_COOKIE['lang'] ?? 'id');
    $l = $l === 'en' ? 'en' : 'id';
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_COOKIE['lang'] = $l;
    /* Persist preferensi bahasa (cookie fungsional — tanpa pelacak, bebas consent) */
    if (isset($_GET['lang']) && !headers_sent()) {
        @setcookie('lang', $l, [
            'expires' => time() + 31536000,
            'path' => BASE_PATH !== '' ? BASE_PATH . '/' : '/',
            'samesite' => 'Lax',
        ]);
    }
    return $l;
}

function t(string $idText, string $enText = ''): string
{
    return lang() === 'en' && $enText !== '' ? $enText : $idText;
}

function lang_switch_url(): string
{
    $qs = $_GET;
    $qs['lang'] = lang() === 'en' ? 'id' : 'en';
    $path = strtok($_SERVER['REQUEST_URI'] ?? '/', '?') ?: '/';
    return e($path . '?' . http_build_query($qs));
}

/* ---------- Slug & data lookups ---------- */
function find_by_slug(array $rows, string $slug): ?array
{
    foreach ($rows as $r) {
        if (($r['slug'] ?? ($r['id'] ?? '')) === $slug) {
            return $r;
        }
    }
    return null;
}
function url_slug(array $row): string
{
    return (string) ($row['slug'] ?? $row['id'] ?? '');
}

/* ---------- Cache file ringan (sitemap, search-index) ----------
   Menghemat CPU shared hosting: hasil generate disimpan di data/cache/.
   Gagal I/O = null (app membangun langsung — selalu berfungsi). */
function cache_get(string $key, int $ttl): ?string
{
    $file = CACHE_DIR . '/' . preg_replace('/[^a-z0-9._-]/i', '_', $key) . '.txt';
    if (!is_file($file) || time() - (int) filemtime($file) > $ttl) {
        return null;
    }
    $c = @file_get_contents($file);
    return $c === false ? null : $c;
}

function cache_set(string $key, string $val): void
{
    if (!is_dir(CACHE_DIR) && !@mkdir(CACHE_DIR, 0755, true)) {
        return;
    }
    @file_put_contents(CACHE_DIR . '/' . preg_replace('/[^a-z0-9._-]/i', '_', $key) . '.txt', $val, LOCK_EX);
}

function cache_clear(): void
{
    if (!is_dir(CACHE_DIR)) {
        return;
    }
    foreach (glob(CACHE_DIR . '/*.txt') ?: [] as $f) {
        @unlink($f);
    }
}

/* ---------- Keamanan: CSP + nonce per request ----------
   Nonce digenerate sekali per request; layout/admin menempelkannya pada
   <script> inline (JSON-LD data block tidak terdampak CSP). */
function csp_nonce(): string
{
    static $n = null;
    if ($n === null) {
        $n = bin2hex(random_bytes(16));
    }
    return $n;
}

function request_is_https(): bool
{
    return (($_SERVER['HTTPS'] ?? '') !== '' && ($_SERVER['HTTPS'] ?? '') !== 'off')
        || (($_SERVER['REQUEST_SCHEME'] ?? '') === 'https')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
}

function send_security_headers(): void
{
    if (headers_sent()) {
        return;
    }
    /* style-src 'unsafe-inline' diperlukan: desain memakai style="" inline.
       Script inline hanya via nonce — handler onclick dipindah ke app.js. */
    header("Content-Security-Policy: default-src 'self'; base-uri 'self'; object-src 'none'; frame-ancestors 'self'; form-action 'self'; img-src 'self' data:; font-src 'self' data:; style-src 'self' 'unsafe-inline'; script-src 'self' 'nonce-" . csp_nonce() . "' https://www.googletagmanager.com https://www.clarity.ms; connect-src 'self' https://www.google-analytics.com https://*.clarity.ms"
        . (request_is_https() ? '; upgrade-insecure-requests' : ''));
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=(), interest-cohort=()');
}

/* ---------- Dimensi gambar (mencegah CLS) ---------- */
function img_dim(string $relPath): string
{
    static $memo = [];
    $file = ROOT_DIR . $relPath;
    if (!isset($memo[$file])) {
        $memo[$file] = is_file($file) ? (getimagesize($file) ?: [0, 0]) : [0, 0];
    }
    [$w, $h] = $memo[$file];
    return $w > 0 ? ' width="' . $w . '" height="' . $h . '"' : '';
}

/* ---------- Notifikasi email pesanan baru (best-effort, tanpa dependensi) ----------
   Memakai mail() bawaan cPanel (SMTP lokal hosting). Deliverability bergantung
   SPF/DKIM domain — lihat docs/SECURITY.md. Gagal email tidak pernah menggagalkan
   penyimpanan pesan. */
function notify_admin_new_message(array $m): bool
{
    $to = trim(setting('notify_email', CONTACT_NOTIFY_EMAIL));
    if ($to === '' || !filter_var($to, FILTER_VALIDATE_EMAIL) || !function_exists('mail')) {
        return false;
    }
    $host = preg_replace('/^www\./', '', (string) (parse_url(setting('site_url', SITE_URL), PHP_URL_HOST) ?: 'localhost'));
    $subject = '[BWN] Pesan baru — ' . ($m['name'] ?? 'pengunjung');
    $body = "Pesan baru dari formulir situs web:\n\n"
        . 'Nama    : ' . ($m['name'] ?? '-') . "\n"
        . 'Telepon : ' . ($m['phone'] ?? '-') . "\n"
        . 'Email   : ' . (($m['email'] ?? '') !== '' ? $m['email'] : '-') . "\n"
        . 'Kota    : ' . (($m['city'] ?? '') !== '' ? $m['city'] : '-') . "\n"
        . 'Jenis   : ' . ($m['order_type'] ?? '-') . "\n\n"
        . "Pesan:\n" . ($m['message'] ?? '-') . "\n\n"
        . 'Waktu   : ' . date('d M Y H:i') . " (WIB)\n"
        . 'Buka CMS: ' . rtrim(setting('site_url', SITE_URL), '/') . "/admin/pesan\n";
    $headers = 'From: BWN Website <no-reply@' . $host . '>' . "\r\n" . "Content-Type: text/plain; charset=UTF-8\r\n";
    return @mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $body, $headers);
}

/** Simulasi lacak pesanan deterministik — paritas dengan versi Next.js (seed hash). */
function order_timeline(string $orderId): array
{
    $seed = crc32($orderId);
    $steps = [
        ['Pesanan Diterima', 'Permintaan Anda tercatat di sistem kami.'],
        ['Verifikasi & Konfirmasi', 'Tim kami mengonfirmasi detail pesanan via WhatsApp.'],
        ['Persiapan & Pengemasan', 'Air bambu dikemas higienis sesuai standar produksi.'],
        ['Serah Terima Kurir', 'Paket diserahkan ke jasa kargo/ekspedisi.'],
        ['Dalam Pengiriman', 'Paket dalam perjalanan ke kota tujuan.'],
        ['Selesai / Diterima', 'Pesanan tiba dan diterima. Terima kasih!'],
    ];
    $progress = 1 + ($seed % 4); // 1..4 tahap selesai
    $eta = 2 + ($seed % 5);
    return ['steps' => $steps, 'progress' => $progress, 'etaDays' => $eta];
}
