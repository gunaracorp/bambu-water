<?php
/**
 * SEO Universalis (v0.5.0) — Alat Submit Massal IndexNow
 * Mengirim seluruh URL (pages + tags) ke jaringan IndexNow (Bing, Yandex, Seznam, Naver).
 *
 * Pakai (dari folder php-cms):
 *   php tools/ping-indexnow.php            # pakai SITE_URL dari config
 *   php tools/ping-indexnow.php https://domain-anda.com
 *
 * Catatan Dewan: satu POST cukup (batas IndexNow 10.000 URL/request).
 */
declare(strict_types=1);

$ROOT = dirname(__DIR__);
require $ROOT . '/config.php';
require ROOT_DIR . '/lib/seo.php';
require ROOT_DIR . '/templates/pages.php';   // core_pages()

$base = rtrim($argv[1] ?? setting('site_url', SITE_URL), '/');
if ($base === '') {
    fwrite(STDERR, "URL dasar wajib — kirim sebagai argumen atau atur SITE_URL di config.php\n");
    exit(1);
}

$urls = [$base . '/'];
foreach (array_keys(core_pages()) as $slug) {
    $urls[] = $base . '/' . $slug;
}
foreach (json_load('articles') as $a) {
    $urls[] = $base . '/artikel/' . ($a['slug'] ?? '');
}
foreach (json_load('cities')['all'] as $c) {
    $urls[] = $base . '/kota/' . ($c['slug'] ?? '');
}
foreach (json_load('conditions')['all'] as $c) {
    $urls[] = $base . '/air-bambu-untuk/' . ($c['slug'] ?? '');
}
foreach (json_load('services')['all'] as $s) {
    $urls[] = $base . '/layanan/' . url_slug($s);
}
foreach (json_load('bamboo') as $b) {
    $urls[] = $base . '/bambu/' . ($b['slug'] ?? '');
}
foreach (seo_tags() as $t) {
    $urls[] = $base . '/tag/' . ($t['slug'] ?? '');
}

echo "Total URL: " . count($urls) . "\n";
echo "Kunci: " . indexnow_key() . "\n";
echo "Mengirim ke api.indexnow.org…\n";

$host = parse_url($base, PHP_URL_HOST) ?: '';
$body = json_encode([
    'host' => $host,
    'key' => indexnow_key(),
    'keyLocation' => $base . '/' . indexnow_key() . '.txt',
    'urlList' => $urls,
], JSON_UNESCAPED_SLASHES);

$ch = curl_init('https://api.indexnow.org/indexnow');
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $body,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json; charset=utf-8'],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
]);
$res = curl_exec($ch);
$code = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
curl_close($ch);

if ($res === false) {
    fwrite(STDERR, "Gagal koneksi — jalankan ulang nanti.\n");
    exit(1);
}
echo match ($code) {
    200, 202 => "✅ HTTP {$code} — DITERIMA. URL akan diproses mesin pencari (umumnya menit–jam).\n",
    400 => "❌ HTTP 400 — format salah.\n",
    403 => "❌ HTTP 403 — kunci tidak cocok dengan file verifikasi.\n",
    422 => "❌ HTTP 422 — URL bukan milik host / kunci salah lokasi.\n",
    429 => "⚠️ HTTP 429 — terlalu sering. Tunggu beberapa menit.\n",
    default => "⚠️ HTTP {$code} — periksa manual.\n",
};
