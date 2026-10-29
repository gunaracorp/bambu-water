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
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? 'cli';
    $ip = trim(explode(',', (string) $ip)[0]);
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
